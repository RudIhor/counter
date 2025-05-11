<?php

declare(strict_types=1);

return [
    'tag.required' => 'Tag is required',
    'tag.min' => 'Tag must be at least 3 characters',
    'tag.max' => 'Tag must be at most 50 characters',
    'count.required' => 'Count is required',
    'count.min' => 'Count must be at least 0',
    'count.max' => 'Count must be at most 1 000 000',
    'created_at.invalid_format' => 'Created at must be a valid date in format YYYY-MM-DD',
    'created_at.timestamp_is_negative' => 'Created at date must be after 00:00:00 UTC on 1 January 1970',
    'created_at.timestamp_is_future' => 'Created at date cannot be in the future',
];
