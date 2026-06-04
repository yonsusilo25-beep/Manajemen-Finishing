<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order {{ $request->request_number }}</title>
    <style>
        @page {
            margin: 24px;
        }

        body {
            color: #1f2937;
            font-family: "DejaVu Sans", Arial, sans-serif;
            font-size: 12px;
            line-height: 1.45;
        }

        .receipt {
            border: 1px solid #d1d5db;
            padding: 18px;
        }

        .header {
            border-bottom: 2px solid #111827;
            margin-bottom: 16px;
            padding-bottom: 12px;
        }

        .brand {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .subtitle {
            color: #6b7280;
            margin: 2px 0 0;
        }

        .document-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            text-align: right;
        }

        .document-number {
            color: #374151;
            font-size: 12px;
            margin: 4px 0 0;
            text-align: right;
        }

        .row {
            clear: both;
            width: 100%;
        }

        .col-left {
            float: left;
            width: 55%;
        }

        .col-right {
            float: right;
            width: 40%;
        }

        .section {
            margin-top: 14px;
        }

        .section-title {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            font-size: 12px;
            font-weight: 700;
            margin: 0 0 8px;
            padding: 6px 8px;
            text-transform: uppercase;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 7px;
            vertical-align: top;
        }

        th {
            background: #f9fafb;
            font-size: 11px;
            text-align: left;
            text-transform: uppercase;
        }

        .label {
            color: #6b7280;
            font-size: 10px;
            text-transform: uppercase;
        }

        .value {
            font-weight: 700;
            margin-top: 2px;
        }

        .muted {
            color: #6b7280;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            border: 1px solid #9ca3af;
            border-radius: 999px;
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            text-transform: uppercase;
        }

        .totals {
            margin-top: 12px;
            width: 42%;
        }

        .signatures {
            margin-top: 36px;
        }

        .signature-box {
            float: left;
            text-align: center;
            width: 33%;
        }

        .signature-line {
            border-top: 1px solid #111827;
            margin: 48px 18px 0;
            padding-top: 6px;
        }

        .footer {
            border-top: 1px dashed #9ca3af;
            color: #6b7280;
            font-size: 10px;
            margin-top: 28px;
            padding-top: 8px;
            text-align: center;
        }

        .clearfix::after {
            clear: both;
            content: "";
            display: table;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header clearfix">
            <div class="col-left">
                <h1 class="brand">Metinca</h1>
                <p class="subtitle">Production Control System</p>
            </div>
            <div class="col-right">
                <h2 class="document-title">PURCHASE ORDER</h2>
                <p class="document-number">{{ $request->request_number }}</p>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-left">
                <table>
                    <tr>
                        <td>
                            <div class="label">Department</div>
                            <div class="value">{{ $request->department->name ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="label">Requestor</div>
                            <div class="value">{{ $request->user->name ?? 'N/A' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="label">Job / Batch</div>
                            <div class="value">{{ $request->job->job_number ?? 'N/A' }}</div>
                            <div class="muted">{{ $request->job->job_name ?? '' }}</div>
                        </td>
                        <td>
                            <div class="label">Product</div>
                            <div class="value">{{ $request->job->product_name ?? 'N/A' }}</div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-right">
                <table>
                    <tr>
                        <td>
                            <div class="label">PO Date</div>
                            <div class="value">{{ $request->request_date->format('d M Y') }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="label">Status</div>
                            <div class="value">
                                <span class="badge">{{ str_replace('_', ' ', $request->status) }}</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="label">Printed At</div>
                            <div class="value">{{ now()->format('d M Y H:i') }}</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="section">
            <h3 class="section-title">Material Items</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 4%;">#</th>
                        <th style="width: 16%;">Code</th>
                        <th>Material</th>
                        <th style="width: 14%;" class="text-right">Requested</th>
                        <th style="width: 14%;" class="text-right">Approved</th>
                        <th style="width: 10%;">Unit</th>
                        <th style="width: 18%;">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($request->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->material->code ?? 'N/A' }}</td>
                            <td>
                                <strong>{{ $item->material->name ?? 'N/A' }}</strong>
                                @if($item->is_additional)
                                    <div class="muted">Additional item</div>
                                @endif
                            </td>
                            <td class="text-right">{{ number_format((float) $item->quantity_requested, 2) }}</td>
                            <td class="text-right">{{ number_format((float) ($item->quantity_approved ?? 0), 2) }}</td>
                            <td>{{ $item->unit ?? $item->material->unit ?? '-' }}</td>
                            <td>{{ $item->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-right">No items available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="clearfix">
            <table class="totals" align="right">
                <tr>
                    <td>Total Items</td>
                    <td class="text-right"><strong>{{ $request->items->count() }}</strong></td>
                </tr>
                <tr>
                    <td>Total Requested Qty</td>
                    <td class="text-right"><strong>{{ number_format((float) $request->items->sum('quantity_requested'), 2) }}</strong></td>
                </tr>
                <tr>
                    <td>Total Approved Qty</td>
                    <td class="text-right"><strong>{{ number_format((float) $request->items->sum('quantity_approved'), 2) }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3 class="section-title">Notes</h3>
            <p>{{ $request->notes ?: 'No additional notes.' }}</p>
        </div>

        <div class="signatures clearfix">
            <div class="signature-box">
                <div class="signature-line">Requested By</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Checked By</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Approved By</div>
            </div>
        </div>

        <div class="footer">
            This document was generated automatically from Purchase Order {{ $request->request_number }}.
        </div>
    </div>
</body>
</html>
