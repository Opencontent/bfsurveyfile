{$question.question_number}. {$question.text}
{concat('/surveyfile/download/',$question.survey_id,'/',$question.answer|explode('/')|implode(':'))|ezurl(no, full)}