<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;


class QuestionsController extends Controller
{
    const DATA_FILE_NAME = 'questions';
    // const DATA_EXTENSION = 'json';
    const DATA_EXTENSION = 'csv';

    const ISO_CODE_REGEX = '/^(aa|ab|ae|af|ak|am|an|ar|as|av|ay|az|az|ba|be|bg|bh|bi|bm|bn|bo|br|bs|ca|ce|ch|co|cr|cs|cu|cv|cy|da|de|dv|dz|ee|el|en|eo|es|et|eu|fa|ff|fi|fj|fo|fr|fy|ga|gd|gl|gn|gu|gv|ha|he|hi|ho|hr|ht|hu|hy|hz|ia|id|ie|ig|ii|ik|io|is|it|iu|ja|jv|ka|kg|ki|kj|kk|kl|km|kn|ko|kr|ks|ku|kv|kw|ky|la|lb|lg|li|ln|lo|lt|lu|lv|mg|mh|mi|mk|ml|mn|mr|ms|mt|my|na|nb|nd|ne|ng|nl|nn|no|nr|nv|ny|oc|oj|om|or|os|pa|pi|pl|ps|pt|qu|rm|rn|ro|ru|rw|sa|sc|sd|se|sg|si|sk|sl|sm|sn|so|sq|sr|ss|st|su|sv|sw|ta|te|tg|th|ti|tk|tl|tn|to|tr|ts|tt|tw|ty|ug|uk|ur|uz|ve|vi|vo|wa|wo|xh|yi|yo|za|zh|zu)$/i';

    /**
     * Retrieve all questions with their chooses translated to the specified language
     * @param Illuminate\Http\Request
     * @return Illuminate\Http\Response
     */
    public function all(Request $request)
    {
        // Validate if has lang param exists
        if (!$request->get('lang')) {
            return response()->json('Wrong format', Response::HTTP_BAD_REQUEST);
        }
        // Validate if lang param is valid
        $targetLang = $request->get('lang');
        if (!preg_match(self::ISO_CODE_REGEX, $targetLang)) {
            return response()->json('Wrong iso code', Response::HTTP_BAD_REQUEST);
        }

        // Create Google Translate instance
        $tr = new GoogleTranslate();
        $tr->setSource();

        // Translate to specified language
        $tr->setTarget($targetLang);

        // Get content data
        $questions = $this->getContentData(self::DATA_FILE_NAME, self::DATA_EXTENSION);

        // Iterate questions to add translations
        foreach ($questions as $i => $question) {

            // Iterate over choices
            foreach ($question['choices'] as $j => $choice) {
                // Translate choice text
                $questions[$i]['choices'][$j]['text'] = $tr->translate($choice['text']);
            }
            // Translate question text
            $questions[$i]['text'] = $tr->translate($question['text']);
        }

        return response()->json($questions, Response::HTTP_OK);

    }


    /**
     * Create a new question
     * @param Illuminate\Http\Request
     * @return Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        return response()->json('Question created', Response::HTTP_CREATED);
    }

    /**
     * Loads questions file from diferent sources.
     *
     * @param string $fileName name of the source file.
     * @param string $extension name of the source extension.
     *
     * @return questions array loaded from the source file.
     */
    private function getContentData($fileName, $extension)
    {
        // Get source file
        $path = storage_path() . '/' . $fileName . '.' . $extension;

        // Validate file exists
        if (!file_exists($path)) return array();

        // Validate extension
        switch($extension) {
            case 'json':
                // Parse json content into array
                $questions = json_decode(file_get_contents($path), true);
                break;
            case 'csv':
                // Parse csv content into array
                $questions = array();

                // Open question file
                $questionFile = fopen($path, 'r');

                // Skip first line
                fgetcsv($questionFile, 0, ",");

                // Iterate csv file
                while (($data = fgetcsv($questionFile, 0, ",")) !== false) {

                    // Create question array
                    $question = array();
                    $question['text'] = $data[0];
                    $question['createdAt'] = $data[1];
                    $question['choices'][]['text'] = $data[2];
                    $question['choices'][]['text'] = $data[3];
                    $question['choices'][]['text'] = $data[4];

                    // Append to result
                    $questions[] = $question;

                    // Get next line
                    $data = fgetcsv($questionFile, 0, ",");
                }
                fclose($questionFile);
                break;
            default:
                // Unknown format
                $questions = array();
                break;
        }

        // Return json decoded data
        return $questions;
    }

}
