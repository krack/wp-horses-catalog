<?php


require_once plugin_dir_path( __FILE__ ).'document.php'; 
class DocumentShortcode
{
    public function __construct()
    {
        add_shortcode('documents', array($this, 'recent_html'));
    }

    public function recent_html($atts, $content)
    {
       
        $html[] = '<table>';
        
        $html[] = '<tr>';
        $html[] = '<th>nom</th>';
        $html[] = '<th>document1</th>';
        $html[] = '<th>document2</th>';
        $html[] = do_shortcode('[protected]'.'<th>tto</th>'.'[/protected]');

        $html[] = '</tr>';
        $documents = Document::getAll(new Search($_GET));

        foreach($documents as $document){
            $html[] = '<tr>';
            $html[] = '<td>'.$document->name.'</td>';
            $html = array_merge($html, $this->addIcon($document->hasDocument1));
            $html = array_merge($html, $this->addIcon($document->hasDocument2));
            $html[] = do_shortcode('[protected]'.'<td>too</td>'.'[/protected]');

            $html[] = '</tr>';
        }
        $html[] = '</table>';

        return  implode('', $html);
    }
    private function addIcon($isPresent){
        $html[] = '<td>';

        $html[] = '<i class="fas ';       
            
        if($isPresent){
            $html[] = 'fa-check';
        }else{ 
            $html[] = 'fa-times';
        }
        $html[] = '"></i></td>';
        $html[] = '</td>';
        return $html;
    }
}