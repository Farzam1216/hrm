<?php


namespace App\Domain\Approval\Actions;

use Illuminate\Support\Facades\DB;

class GetFieldCategories
{
    /**
     * @param mixed $fields
     * @return groupCategory
     */
    public function execute($fields)
    {
        $categoriesFields = DB::table('approval_form_fields')->get();
        $categories = [];
        foreach ($categoriesFields as $key => $field) {
            if (array_key_exists($field->group, $categories)) {
                $categories[$field->group] = $categories[$field->group] + [
                    $field->name => [
                        $field->field_name, 'details' => [
                            'model' => $field->model,
                            'type' => $field->type,
                            'content' => json_decode($field->content, true),
                        ],
                    ],
                ];
            } else {
                $categories[$field->group] = [
                    $field->name => [
                        $field->field_name,
                        'details' => [
                            'model' => $field->model,
                            'type' => $field->type,
                            'content' => json_decode($field->content, true),
                        ],
                    ],
                ];
            }
        }
        if ($fields == 'all') {
            return $categories;
        }

        $requiredGroup = [];
        if (isset($fields['field_data'])) {
            foreach ($fields['field_data'] as $field => $data) {
                $data = str_replace(['[', ']'], '', $data);
                $data = explode(',', $data);
                if ('visa_note' == $field) {
                    $requiredGroup['Visa']['note'] = [
                        'name' => trim($data[0]),
                        'status' => trim($data[1]),
                        'model' => 'EmployeeVisa',
                        'type' => 'text',
                        'content' => null,
                    ];
                    continue;
                }

                if ($field == 'dependent_date_of_birth') {
                    $requiredGroup['Benefits']['date_of_birth'] = [
                        'name' => trim($data[0]),
                        'status' => trim($data[1]),
                        'model' => 'EmployeeDependent',
                        'type' => 'date',
                        'content' => null,
                    ];
                    continue;
                }

                if ($field == 'dependent_gender') {
                    $requiredGroup['Benefits']['gender'] = [
                        'name' => trim($data[0]),
                        'status' => trim($data[1]),
                        'model' => 'EmployeeDependent',
                        'type' => 'list',
                        'content' => ['fixed' => '1', 'options' => ['male' => 'Male', 'female' => 'Female']],
                    ];
                    continue;
                }

                foreach ($categories as $category => $types) {
                    foreach ($types as $name => $fieldName) {
                        if (isset($types[$name]) && $types[$name][0] == $field && $name == $data[0]) {
                            $requiredGroup[$category][$field] = [
                                'name' => trim($data[0]),
                                'status' => trim($data[1]),
                                'model' => $fieldName['details']['model'],
                                'type' => $fieldName['details']['type'],
                                'content' => $fieldName['details']['content'],
                            ];
                        }
                    }
                }
            }
        }

        return $requiredGroup;
    }
}
