<?php

if (true
    && (false
        || true
    )
) {
    echo 'bar';
}

if (true) {
    echo 'bar';
} elseif (false
    && (true
        || false
    )
) {
    echo 'baz';
}

while (true
    && (false
        || true
    )
) {
    echo 'bar';
}

if ((true
        && $condition1
        || $condition2
    )
    || ($condition3
        && $condition4
    )
) {
    echo 'bar';
}

if ($condition1
    && ($condition2
        && $condition3
        || ($condition4
            && $condition5
            || ($condition6 || $condition7)
        )
    )
) {
    echo 'bar';
}

if ($condition1
    && ($condition2
        && $condition3
        || (
            $condition4
            && $condition5
        )
    )
) {
    echo 'bar';
}
