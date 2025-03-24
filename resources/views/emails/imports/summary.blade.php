@component('mail::message')
# Employee Import Summary

**Import Job #{{ $importJob->id }}** has been completed.

## Import Statistics:
@component('mail::table')
| Metric | Value |
|:-------|:------|
| Total Records | {{ number_format($stats['total_records']) }} |
| Successfully Processed | {{ number_format($stats['processed_records'] - $stats['failed_records']) }} |
| Failed Records | {{ number_format($stats['failed_records']) }} |
| Success Rate | {{ $stats['success_rate'] }}% |
| Processing Duration | {{ gmdate('H:i:s', $stats['duration']) }} |
| Performance | {{ $stats['records_per_second'] }} records/second |
| Completed At | {{ $stats['completed_at'] }} |
@endcomponent

@if($stats['failed_records'] > 0)
    ## Warning
    This import had {{ $stats['failed_records'] }} failed records. You may want to review the logs for details.
@endif

@component('mail::button', ['url' => route('import.status', $importJob->id)])
View Import Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent