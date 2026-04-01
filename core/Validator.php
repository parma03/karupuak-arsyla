<?php
// core/Validator.php
namespace Core;

class Validator
{
    private array $errors = [];

    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $ruleStr) {
            $value     = $data[$field] ?? '';
            $ruleList  = explode('|', $ruleStr);

            foreach ($ruleList as $rule) {
                if ($rule === 'required' && empty(trim((string)$value))) {
                    $this->errors[$field][] = ucfirst($field) . ' wajib diisi.';
                } elseif (str_starts_with($rule, 'min:')) {
                    $min = (int)substr($rule, 4);
                    if (strlen((string)$value) < $min) {
                        $this->errors[$field][] = ucfirst($field) . " minimal $min karakter.";
                    }
                } elseif (str_starts_with($rule, 'max:')) {
                    $max = (int)substr($rule, 4);
                    if (strlen((string)$value) > $max) {
                        $this->errors[$field][] = ucfirst($field) . " maksimal $max karakter.";
                    }
                } elseif ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = 'Format email tidak valid.';
                } elseif (str_starts_with($rule, 'numeric') && !is_numeric($value)) {
                    $this->errors[$field][] = ucfirst($field) . ' harus berupa angka.';
                } elseif (str_starts_with($rule, 'min_val:')) {
                    $minVal = (float)substr($rule, 8);
                    if ((float)$value < $minVal) {
                        $this->errors[$field][] = ucfirst($field) . " minimal $minVal.";
                    }
                }
            }
        }

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(string $field): ?string
    {
        return $this->errors[$field][0] ?? null;
    }

    public function hasError(string $field): bool
    {
        return isset($this->errors[$field]);
    }
}
