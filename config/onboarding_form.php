<?php

return [
    'sections' => [
        'basic' => [
            'title' => 'Basic Information',
            'show_section_key' => 'section_show_basic',
            'is_show_section_disabled' => true,
            'fields' => [
                [
                    'slug' => 'basic_first_name',
                    'label' => 'First Name',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => true,
                ],
                [
                    'slug' => 'basic_last_name',
                    'label' => 'Last Name',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => true,
                ],
                [
                    'slug' => 'basic_email',
                    'label' => 'Email',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => true,
                ],
                [
                    'slug' => 'basic_phone',
                    'label' => 'Phone',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => true,
                ],
                [
                    'slug' => 'basic_gender',
                    'label' => 'Gender',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => true,
                ],
                [
                    'slug' => 'basic_date_of_birth',
                    'label' => 'Date of Birth',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => true,
                ],
                [
                    'slug' => 'basic_present_address',
                    'label' => 'Present Address',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'basic_permanent_address',
                    'label' => 'Permanent Address',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'basic_passport_number',
                    'label' => 'Passport Number',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'basic_passport_attachment',
                    'label' => 'Passport Attachment',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
            ],
        ],
        'academic' => [
            'title' => 'Academic Information',
            'show_section_key' => 'section_show_academic',
            'is_show_section_disabled' => false,
            'fields' => [
                [
                    'slug' => 'academic_certificate_type_id',
                    'label' => 'Degree Name',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => true,
                ],
                [
                    'slug' => 'academic_institution',
                    'label' => 'Institute Name',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'academic_academic_year',
                    'label' => 'Academic Year',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'academic_passing_year',
                    'label' => 'Passing Year',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'academic_result',
                    'label' => 'Result',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'academic_attachment',
                    'label' => 'Attachment',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
            ],
        ],
        'work_experience' => [
            'title' => 'Work Experience',
            'show_section_key' => 'section_show_work_experience',
            'is_show_section_disabled' => false,
            'fields' => [
                [
                    'slug' => 'work_experience_title',
                    'label' => 'Title',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => true,
                ],
                [
                    'slug' => 'work_experience_company',
                    'label' => 'Company',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'work_experience_designation',
                    'label' => 'Designation',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'work_experience_start_date',
                    'label' => 'Start Date',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'work_experience_end_date',
                    'label' => 'End Date',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'work_experience_description',
                    'label' => 'Description',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'work_experience_attachment',
                    'label' => 'Attachment',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
            ]
        ],
        'extra_curriculum_activity' => [
            'title' => 'Extra Curricular Activities',
            'show_section_key' => 'section_show_extra_curriculum_activity',
            'is_show_section_disabled' => false,
            'fields' => [
                [
                    'slug' => 'extra_curriculum_activity_title',
                    'label' => 'Title',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => true,
                ],
                [
                    'slug' => 'extra_curriculum_activity_description',
                    'label' => 'Description',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'extra_curriculum_activity_attachment',
                    'label' => 'Attachment',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ]
            ]
        ],
        'language_proficiency' => [
            'title' => 'Language Proficiency',
            'show_section_key' => 'section_show_language_proficiency',
            'is_show_section_disabled' => false,
            'fields' => [
                [
                    'slug' => 'language_proficiency_test_id',
                    'label' => 'Proficiency Test',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => true,
                ],
                [
                    'slug' => 'language_proficiency_score',
                    'label' => 'Score',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'language_proficiency_test_date',
                    'label' => 'Test Date',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'language_proficiency_expired_date',
                    'label' => 'Expired Date',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'language_proficiency_attachment',
                    'label' => 'Attachment',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
            ]
        ],
        'interested_area' => [
            'title' => 'Interested Area',
            'show_section_key' => 'section_show_interested_area',
            'is_show_section_disabled' => false,
            'fields' => [
                [
                    'slug' => 'interested_area_destination_country_ids',
                    'label' => 'Preferable Country',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => true,
                ],
                [
                    'slug' => 'interested_area_university_ids',
                    'label' => 'Preferable University',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'interested_area_subject_ids',
                    'label' => 'Preferable Subject',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ],
                [
                    'slug' => 'interested_area_admission_period',
                    'label' => 'Preferable Intake Year/Session',
                    'show_key' => 'field_show',
                    'required_key' => 'field_required',
                    'is_disabled' => false,
                ]
            ]
        ],
    ],
];
