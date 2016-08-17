<?php if (isset($param)) { ?>
   <h3>some text</h3>
<?php }

if ($condition1
    && $condition2
    && $condition3
) {
    echo 'bar';
}

if (($condition1
    || $condition2)
    && $condition3
    && $condition4
    && $condition5
) {
    echo 'bar';
}

if (($condition1 || $condition2) && $condition3 && $condition4 && $condition5) {
    echo 'bar';
}

if (($condition1 || $condition2)
    && $condition3
) {
    echo 'bar';
}

if (($condition1
    || $condition2)
) {
    echo 'bar';
}

if (($condition1
    || $condition2)
    && $condition3 &&
    $condition4
) {
    echo 'bar';
}

if (($condition1
   || $condition2)
      && $condition3
   && $condition4
   && $condition5
) {
    echo 'bar';
}

if (($condition1
    || $condition2)
 ) {
    echo 'bar';
}

if ($condition1
    || $condition2
    || $condition3
) {
    echo 'bar';
} elseif ($condition1
    || $condition2
    || $condition3
) {
    echo 'bar';
}

if ($condition1
    || $condition2
    || $condition3
) {
    echo 'bar';
} elseif ($condition1
   || $condition2 &&
    $condition3
) {
    echo 'bar';
}

if ($condition1
    || $condition2
|| $condition3) {
    echo 'bar';
}

if ($condition1
    || $condition2 || $condition3
) {
    echo 'bar';
}

if (!empty($post)
    && (!empty($context['header'])
    xor stripos($context['header'], 'Content-Type'))
) {
    echo 'bar';
}

if ($foo) {
    echo 'bar';
}

// Should be no errors even though lines are
// not exactly aligned together. Multi-line function
// call takes precedence.
if (array_key_exists($key, $value)
    && array_key_exists(
        $key,
        $value2
    )
) {
    echo 'bar';
}

if (true) :
    $foo = true;
endif;

if ($IPP->errorCode() == 401 || // comment
    $IPP->errorCode() == 3200) {
    return false;
}

if ($IPP->errorCode() == 401 || // comment
    $IPP->errorCode() == 3200   // long comment here
) {
    return false;
}

if ($IPP->errorCode() == 401
    // Comment explaining the next condition here.
    || $IPP->errorCode() == 3200
) {
    return false;
}

while (true) {
    echo 'bar';
}

while (true
    && false
) {
    echo 'bar';
}

while (true
       && false
) {
    echo 'bar';
}

while (true &&
    false
    || true) {
    echo 'bar';
}
