# Section/question/answer seeder

Files **questionnaire_texts_it.json** and **questionnaire_metadata.json** contain all section/question/answer information.
They share the same hierarchical structure: list of sections containing a list of questions containing a list of answers.

 * **questionnaire_texts_it.json** contains section/question/answer localized texts.

 * **questionnaire_metadata.json** contains other section/question/answer information, only question-related
and used during validation so far:

    - **type**: single_choice, multiple_choice, text
    - **required**
    - **options**: a list of custom attributes:
      - **makes_next_required**: whether the question makes the following questions mandatory
        - **next**: how many questions after the current are made mandatory
        - **answer**: which answer of the current question makes the next mandatory

### Examples of manual section/question/answer management

 * section addition:
    ```bash
    php artisan tinker
    >>> $section = new App\Models\Section();
    >>> $section->title = 'Note';
    >>> $section->position = ()App\Models\Section::count() + 1);
    >>> $section->save();
    ```
* question addition:
    ```bash
    php artisan tinker
    >>> $section = App\Models\Section::where('title', 'Note')->first();
    >>> $question = new App\Models\Question();
    >>> $question->text = 'Note personali';
    >>> $question->position = ($section->questions->count() + 1); // question is appended to section
    >>> $question->type = 'text';
    >>> $question->required = false;
    >>> $section->questions()->save($question);
    ```