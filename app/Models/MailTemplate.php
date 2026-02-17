<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'subject',
        'body_html',
        'body_text',
        'available_placeholders',
        'is_active',
    ];

    protected $casts = [
        'available_placeholders' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Replace placeholders in the template with actual values
     */
    public function render(array $data): string
    {
        $body = $this->body_html;
        
        foreach ($data as $key => $value) {
            $body = str_replace('{{' . $key . '}}', $value, $body);
        }
        
        return $body;
    }

    /**
     * Get subject with placeholders replaced
     */
    public function getSubject(array $data): string
    {
        $subject = $this->subject;
        
        foreach ($data as $key => $value) {
            $subject = str_replace('{{' . $key . '}}', $value, $subject);
        }
        
        return $subject;
    }
}
