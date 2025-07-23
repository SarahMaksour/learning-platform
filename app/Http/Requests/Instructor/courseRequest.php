<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;

class courseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|max:2048',
            'videos' => 'required|array|min:1',
            'videos.*.title' => 'required|string|max:255',
          //  'videos.*.description' => 'required|string|max:1000',
            'videos.*.video' => 'required|file|mimes:mp4,mov,avi|max:50000',
            'videos.*.duration' => 'nullable|numeric',
            'videos.*.quiz' => 'nullable|array',
           // 'videos.*.quiz.title' => 'required_with:videos.*.quiz|string|max:255',
           // 'videos.*.quiz.total_point' => 'required_with:videos.*.quiz|numeric|min:0',
            'videos.*.quiz.questions' => 'required_with:videos.*.quiz|array|min:1',
            'videos.*.quiz.questions.*.text' => 'required|string',
            'videos.*.quiz.questions.*.options' => 'required|array|min:2',
            'videos.*.quiz.questions.*.correct_answer' => 'required|string',
        //    'videos.*.quiz.questions.*.points' => 'nullable|integer|min:1',
     
        ];
    }
}
