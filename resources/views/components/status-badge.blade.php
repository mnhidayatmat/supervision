@props(['status'])

@php
$map = [
    'backlog' => ['gray', 'Backlog'],
    'planned' => ['blue', 'Planned'],
    'in_progress' => ['yellow', 'In Progress'],
    'waiting_review' => ['orange', 'Waiting Review'],
    'revision' => ['purple', 'Revision'],
    'completed' => ['green', 'Completed'],
    'draft' => ['gray', 'Draft'],
    'submitted' => ['blue', 'Submitted'],
    'reviewed' => ['green', 'Reviewed'],
    'revision_needed' => ['orange', 'Revision Needed'],
    'accepted' => ['green', 'Accepted'],
    'pending' => ['yellow', 'Pending'],
    'active' => ['green', 'Active'],
    'inactive' => ['gray', 'Inactive'],
    'on_hold' => ['orange', 'On Hold'],
    'withdrawn' => ['red', 'Withdrawn'],
    'not_started' => ['gray', 'Not Started'],
    'scheduled' => ['blue', 'Scheduled'],
    'cancelled' => ['red', 'Cancelled'],
    'verified' => ['green', 'Verified'],
];
[$color, $label] = $map[$status] ?? ['gray', ucfirst(str_replace('_', ' ', $status))];
@endphp

<x-badge :color="$color">{{ $label }}</x-badge>
