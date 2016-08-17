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
