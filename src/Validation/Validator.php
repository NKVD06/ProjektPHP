<?php

class Validator
{
    private array $errors = [];
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function required(string $field, string $message = null): self
    {
        $value = trim($this->data[$field] ?? '');
        
        if (empty($value)) {
            $this->errors[$field] = $message ?? "{$field} is required";
        }
        
        return $this;
    }

    public function minLength(string $field, int $length, string $message = null): self
    {
        $value = trim($this->data[$field] ?? '');
        
        if (strlen($value) < $length) {
            $this->errors[$field] = $message ?? "{$field} must be at least {$length} characters";
        }
        
        return $this;
    }

    public function numeric(string $field, string $message = null): self
    {
        $value = $this->data[$field] ?? '';
        
        if (!is_numeric($value) || $value <= 0) {
            $this->errors[$field] = $message ?? "{$field} must be a valid positive number";
        }
        
        return $this;
    }

    public function url(string $field, string $message = null): self
    {
        $value = trim($this->data[$field] ?? '');
        
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
            $this->errors[$field] = $message ?? "{$field} must be a valid URL";
        }
        
        return $this;
    }

    public function sanitize(string $field): string
    {
        return htmlspecialchars(trim($this->data[$field] ?? ''), ENT_QUOTES, 'UTF-8');
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}