<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ClearanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClearanceRequestController extends Controller
{
    private function isAdmin(): bool
    {
        return session('admin_logged_in') === true && in_array(session('admin_role'), ['admin', 'official'], true);
    }

    private function forbidUnlessAdmin()
    {
        if (! $this->isAdmin()) {
            abort(403, 'Forbidden');
        }
    }

    public function index(Request $request)
    {
        $isAdmin = $this->isAdmin();

        $query = ClearanceRequest::query()->latest('id');

        if (! $isAdmin) {
            $ownerKey = trim(strtolower((string) $request->query('owner_key', '')));
            $email = trim(strtolower((string) $request->query('email', '')));
            $ownerName = trim(strtolower((string) $request->query('owner_name', '')));

            if ($ownerKey === '' && $email === '' && $ownerName === '') {
                return response()->json([
                    'message' => 'owner_key, owner_name, or email is required',
                ], 422);
            }

            $query->where(function ($builder) use ($ownerKey, $email, $ownerName) {
                $hasClause = false;

                if ($ownerKey !== '') {
                    $builder->whereRaw('LOWER(owner_key) = ?', [$ownerKey]);
                    $hasClause = true;
                }

                if ($email !== '') {
                    if ($hasClause) {
                        $builder->orWhereRaw('LOWER(email) = ?', [$email])
                            ->orWhereRaw('LOWER(owner_email) = ?', [$email]);
                    } else {
                        $builder->whereRaw('LOWER(email) = ?', [$email])
                            ->orWhereRaw('LOWER(owner_email) = ?', [$email]);
                        $hasClause = true;
                    }
                }

                if ($ownerName !== '') {
                    if ($hasClause) {
                        $builder->orWhereRaw('LOWER(owner_name) = ?', [$ownerName])
                            ->orWhereRaw('LOWER(name) = ?', [$ownerName]);
                    } else {
                        $builder->whereRaw('LOWER(owner_name) = ?', [$ownerName])
                            ->orWhereRaw('LOWER(name) = ?', [$ownerName]);
                    }
                }
            });
        }

        $requests = $query->get()->map(fn (ClearanceRequest $item) => $this->transform($item))->values();

        return response()->json([
            'data' => $requests,
        ]);
    }

    public function update(Request $request, string $ref)
    {
        $this->forbidUnlessAdmin();

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,approved,rejected'],
        ]);

        $clearanceRequest = ClearanceRequest::where('ref', $ref)->firstOrFail();
        $clearanceRequest->status = $validated['status'];
        $clearanceRequest->save();

        return response()->json([
            'message' => 'Request updated successfully.',
            'request' => $this->transform($clearanceRequest->fresh()),
        ]);
    }

    public function destroy(string $ref)
    {
        $this->forbidUnlessAdmin();

        $clearanceRequest = ClearanceRequest::where('ref', $ref)->firstOrFail();
        $filePath = $clearanceRequest->id_file_path;

        if ($filePath && Storage::disk('local')->exists($filePath)) {
            Storage::disk('local')->delete($filePath);
        }

        $clearanceRequest->delete();

        return response()->json([
            'message' => 'Request deleted successfully.',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ref' => ['nullable', 'string', 'max:100'],
            'owner_key' => ['required', 'string', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'owner_email' => ['nullable', 'email', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'age' => ['nullable', 'string', 'max:20'],
            'contact' => ['nullable', 'string', 'max:50'],
            'purpose' => ['required', 'string', 'max:255'],
            'purpose_reason' => ['required', 'string', 'max:500'],
            'idfile' => ['required', 'image', 'max:10240'],
            'pdf_saved' => ['nullable', 'boolean'],
            'saved_cert_type' => ['nullable', 'string', 'max:255'],
            'saved_template' => ['nullable', 'string'],
            'saved_paper_html' => ['nullable', 'string'],
        ]);

        $ref = trim((string) ($validated['ref'] ?? ''));
        if ($ref === '') {
            $ref = 'BR' . now()->format('YmdHis') . Str::upper(Str::random(5));
        }

        $file = $request->file('idfile');
        $extension = strtolower((string) $file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'jpg');
        $fileName = Str::slug($ref) . '-' . Str::random(12) . '.' . $extension;
        $filePath = $file->storeAs('clearance-ids', $fileName);

        $clearanceRequest = ClearanceRequest::create([
            'ref' => $ref,
            'owner_key' => $validated['owner_key'],
            'owner_name' => $validated['owner_name'] ?? null,
            'owner_email' => $validated['owner_email'] ?? null,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'age' => $validated['age'] ?? null,
            'contact' => $validated['contact'] ?? null,
            'purpose' => $validated['purpose'],
            'purpose_reason' => $validated['purpose_reason'],
            'status' => 'pending',
            'date_requested' => now()->toDateString(),
            'valid_until' => now()->addYear()->toDateString(),
            'id_file_name' => $file->getClientOriginalName(),
            'id_file_path' => $filePath,
            'id_file_mime' => $file->getClientMimeType(),
            'pdf_saved' => (bool) ($validated['pdf_saved'] ?? false),
            'saved_cert_type' => $validated['saved_cert_type'] ?? null,
            'saved_template' => isset($validated['saved_template']) ? json_decode($validated['saved_template'], true) : null,
            'saved_paper_html' => $validated['saved_paper_html'] ?? null,
        ]);

        return response()->json([
            'message' => 'Application submitted successfully.',
            'request' => $this->transform($clearanceRequest),
        ], 201);
    }

    public function image(string $ref)
    {
        $requestRecord = ClearanceRequest::where('ref', $ref)->firstOrFail();
        $path = $requestRecord->id_file_path;

        if (! $path || ! Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return response()->file(Storage::disk('local')->path($path));
    }

    public function exportResidentReport()
    {
        $this->forbidUnlessAdmin();

        $requests = ClearanceRequest::query()
            ->where('status', 'approved')
            ->latest('id')
            ->get();
        $xml = $this->buildResidentReportWorkbook($requests);
        $filename = 'approved-certificate-report-' . now()->format('Y-m-d') . '.xls';

        return response($xml, 200)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function buildResidentReportWorkbook($requests): string
    {
        $sections = [
            'clearance' => 'Barangay Clearance',
            'indigency' => 'Certificate of Indigency',
            'good_moral' => 'Good Moral',
            'oneness' => 'Certificate of Oneness',
            'job_seeker' => 'First-Time Job Seeker',
            'oath' => 'Oath of Undertaking',
            'other' => 'Other Certificates',
        ];

        $grouped = [];
        foreach (array_keys($sections) as $key) {
            $grouped[$key] = [];
        }

        foreach ($requests as $request) {
            $row = $this->transformForResidentReport($request);
            $grouped[$row['section']][] = $row;
        }

        $summaryRows = [];
        $summaryRows[] = $this->reportRow([
            'Section',
            'Total',
            'Pending',
            'Approved',
            'Rejected',
        ], true);

        foreach ($sections as $key => $label) {
            $rows = $grouped[$key];
            $summaryRows[] = $this->reportRow([
                $label,
                count($rows),
                count(array_filter($rows, fn ($row) => $row['status'] === 'pending')),
                count(array_filter($rows, fn ($row) => $row['status'] === 'approved')),
                count(array_filter($rows, fn ($row) => $row['status'] === 'rejected')),
            ]);
        }

        $sheetXml = [];
            $sheetXml[] = $this->reportWorksheet('Summary', array_merge([
            $this->reportTitleRow('Approved Resident Certificate Requests Report'),
            $this->reportBlankRow(),
            $this->reportRow(['Generated At', now()->toDateTimeString()]),
            $this->reportRow(['Approved Requests', $requests->count()]),
            $this->reportBlankRow(),
        ], $summaryRows));

        foreach ($sections as $key => $label) {
            $rows = $grouped[$key];
            $tableRows = [];
            $tableRows[] = $this->reportRow([
                '#',
                'Resident',
                'Email',
                'Contact',
                'Certificate',
                'Reference',
                'Purpose',
                'Status',
                'Date Requested',
            ], true);

            foreach ($rows as $index => $row) {
                $tableRows[] = $this->reportRow([
                    $index + 1,
                    $row['name'],
                    $row['email'],
                    $row['contact'],
                    $row['certificate'],
                    $row['ref'],
                    $row['purpose'],
                    ucfirst($row['status']),
                    $row['date'],
                ]);
            }

            if (! count($rows)) {
                $tableRows[] = $this->reportRow(['No requests found for this section.']);
            }

            $sheetXml[] = $this->reportWorksheet($label, array_merge([
                $this->reportTitleRow($label),
                $this->reportBlankRow(),
            ], $tableRows));
        }

        return '<?xml version="1.0" encoding="UTF-8"?>' .
            '<?mso-application progid="Excel.Sheet"?>' .
            '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" ' .
            'xmlns:o="urn:schemas-microsoft-com:office:office" ' .
            'xmlns:x="urn:schemas-microsoft-com:office:excel" ' .
            'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' .
            implode('', $sheetXml) .
            '</Workbook>';
    }

    private function transformForResidentReport(ClearanceRequest $item): array
    {
        $certificate = trim((string) ($item->saved_cert_type ?: data_get($item->saved_template, 'certificateType') ?: $item->purpose ?: $item->purpose_reason ?: 'Other Certificate'));
        $section = $this->resolveReportSection($certificate, $item->purpose, $item->purpose_reason);

        return [
            'name' => trim((string) ($item->name ?: $item->owner_name ?: '')),
            'email' => trim((string) ($item->email ?: $item->owner_email ?: '')),
            'contact' => trim((string) ($item->contact ?: '')),
            'certificate' => $certificate,
            'ref' => trim((string) $item->ref),
            'purpose' => trim((string) ($item->purpose_reason ?: $item->purpose ?: '')),
            'status' => trim((string) ($item->status ?: 'pending')),
            'date' => optional($item->date_requested)->toDateString() ?: '',
            'section' => $section,
        ];
    }

    private function resolveReportSection(string $certificate, ?string $purpose = null, ?string $reason = null): string
    {
        $text = mb_strtolower(trim($certificate . ' ' . ($purpose ?? '') . ' ' . ($reason ?? '')));

        if ($text === '') {
            return 'other';
        }
        if (str_contains($text, 'clearance')) return 'clearance';
        if (str_contains($text, 'indigency')) return 'indigency';
        if (str_contains($text, 'good moral')) return 'good_moral';
        if (str_contains($text, 'oneness')) return 'oneness';
        if (str_contains($text, 'first-time job seeker') || str_contains($text, 'first time job seeker') || str_contains($text, 'job seeker')) return 'job_seeker';
        if (str_contains($text, 'oath of undertaking')) return 'oath';
        return 'other';
    }

    private function reportWorksheet(string $name, array $rows): string
    {
        $sheetName = $this->xmlEscape(substr($name, 0, 31));
        return '<Worksheet ss:Name="' . $sheetName . '"><Table>' . implode('', $rows) . '</Table></Worksheet>';
    }

    private function reportTitleRow(string $title): string
    {
        return $this->reportRow([$title], true);
    }

    private function reportBlankRow(): string
    {
        return '<Row><Cell><Data ss:Type="String"></Data></Cell></Row>';
    }

    private function reportRow(array $values, bool $header = false): string
    {
        $cells = [];
        foreach ($values as $value) {
            $text = $this->xmlEscape((string) $value);
            $type = $header || ! is_numeric($value) ? 'String' : 'Number';
            $cells[] = '<Cell><Data ss:Type="' . $type . '">' . $text . '</Data></Cell>';
        }

        return '<Row>' . implode('', $cells) . '</Row>';
    }

    private function xmlEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    private function transform(ClearanceRequest $item): array
    {
        return [
            'id' => $item->id,
            'ref' => $item->ref,
            'ownerKey' => $item->owner_key,
            'ownerName' => $item->owner_name,
            'ownerEmail' => $item->owner_email,
            'name' => $item->name,
            'email' => $item->email,
            'address' => $item->address,
            'age' => $item->age,
            'contact' => $item->contact,
            'purpose' => $item->purpose,
            'purposeReason' => $item->purpose_reason,
            'status' => $item->status,
            'dateRequested' => optional($item->date_requested)->toDateString(),
            'validUntil' => optional($item->valid_until)->toDateString(),
            'idFileName' => $item->id_file_name,
            'idFilePath' => $item->id_file_path,
            'idFileMime' => $item->id_file_mime,
            'idFileUrl' => route('clearance-requests.image', ['ref' => $item->ref]),
            'pdfSaved' => (bool) $item->pdf_saved,
            'savedCertType' => $item->saved_cert_type,
            'savedTemplate' => $item->saved_template,
            'savedPaperHtml' => $item->saved_paper_html,
        ];
    }
}
