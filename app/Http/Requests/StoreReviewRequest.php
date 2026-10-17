<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    /**
     * On a mistake the visitor lands back on the form, not at the top of the page.
     */
    protected $redirect = '/reviews#rv-share';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        // Links in a review are almost always spam.
        $noLinks = 'not_regex:/https?:\/\/|www\./i';

        return [
            'name' => ['required', 'string', 'min:2', 'max:60', $noLinks],
            'place' => ['nullable', 'string', 'max:80', $noLinks],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'min:10', 'max:400', $noLinks],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please tell us your name.',
            'rating.required' => 'Please choose a star rating.',
            'rating.between' => 'Please choose a star rating from 1 to 5.',
            'comment.required' => 'Please write a few words about your stay.',
            'comment.min' => 'Please write at least :min characters.',
            'comment.max' => 'Please keep your review under :max characters.',
            'not_regex' => 'Please leave web links out of your review.',
        ];
    }
}
