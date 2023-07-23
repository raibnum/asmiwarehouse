<?php

function persenCompress($size)
{
  $persen = 75;
  $size = $size / 1024;
  if ($size >= 8192) {
    $persen = 15;
  } else if ($size >= 7168) {
    $persen = 25;
  } else if ($size >= 6144) {
    $persen = 35;
  } else if ($size >= 5120) {
    $persen = 45;
  } else if ($size >= 4096) {
    $persen = 55;
  } else if ($size >= 3072) {
    $persen = 65;
  }
  return $persen;
}
