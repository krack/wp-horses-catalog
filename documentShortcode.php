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

        $search = new Search($_GET);
        $html[] = '<div class="documents-shortcode">';
        $html = array_merge($html, $this->addSearch($search));
       
        $html[] = '<table>';

        $html[] = '<tr>';
        $html[] = '<th rowspan="2">'.__('Stallion name', 'horses-catalog').'</th>';
        $html[] = '<th rowspan="2" class="fulltext">'.__('Plugs<br /> sports performances<br /> from production', 'horses-catalog').'</th>';
        $html[] = '<th rowspan="2" class="reduc">'.__('Perf', 'horses-catalog').'<i class="fas fa-info-circle" title="'.__('Plugs\nsports performances\nfrom production', 'horses-catalog').'"></i></th>';
        $html[] = '<th rowspan="2" class="fulltext">'.__('Specifications sheet<br /> transmissible model,<br /> gaits and free jumping', 'horses-catalog').'</th>';
        $html[] = '<th rowspan="2" class="reduc">'.__('Caract', 'horses-catalog').'<i class="fas fa-info-circle" title="'.__('Specifications sheet<\ntransmissible model,\ngaits and free jumping', 'horses-catalog').'"></i></th>';
        $html[] = '<th rowspan="1" colspan="2"  class="fulltext">'.__('Number of products<br /> appraised in Model - Gaits -<br /> Free Jumping as', 'horses-catalog').'</th>';
        $html[] = '<th rowspan="1" colspan="2"  class="reduc">'.__('Products', 'horses-catalog').'<br /><i class="fas fa-info-circle" title="'.__('Number of products\nappraised in Model - Gaits -\nFree Jumping as', 'horses-catalog').'"></i></th>';
        $html[] = '<th rowspan="2" class="fulltext">'.__('PDF<br/> card', 'horses-catalog').'</th>';
        $html[] = '<th rowspan="2" class="reduc">'.__('PDF', 'horses-catalog').'</th>';

        $html[] = '<tr>';
        $html[] = '<th>'.__('Father', 'horses-catalog').'</th>';
        $html[] = '<th>'.__('Mother of father', 'horses-catalog').'</th>';

        $html[] = '</tr>';
        $documents = Document::getAll($search);

        foreach($documents as $document){
            $html[] = '<tr>';
            $html[] = '<td>'.$document->name.'</td>';
            $html = array_merge($html, $this->addIcon($document->hasDocumentPerf));
            $html = array_merge($html, $this->addIcon($document->hasDocumentCarateritic));
            $html[] = '<td>'.$document->productFatherCount.'</td>';
            $html[] = '<td>'.$document->hasDocumentCarateriticCount.'</td>';
            $html[] = '<td>';
            $query_fiche_args = array(
                'post_type'      => 'attachment',
                'post_mime_type' => 'application/pdf',
                'post_status'    => 'inherit',
                'posts_per_page' => -1,
                'post_parent'    => 0,
                'starts_with'    => $document->id
                
                
            );
            $query_fiche = new WP_Query( $query_fiche_args );
            if(count($query_fiche->posts) > 0){
            ?>
                <?php
                    foreach ( $query_fiche->posts as $pdf ) {
                        $html[] = do_shortcode('[protected visible="true" message="'.__('Connect yourself<br /> to consult', 'horses-catalog').'"]'.'<a download class="no-pdf-style" href="'.wp_get_attachment_url( $pdf->ID ).'"><span class="fulltext" >'.__('Consult the PDF file of the horse', 'horses-catalog').'</span><i class="fas fa-download reduc"></i></a>'.'[/protected]');
                    }
                ?>
        
            <?php  
            }

            $html[] = '</td>';

           

            $html[] = '</tr>';
        }
        $html[] = '</table>';
        $html[] = '</div>';

        return  implode('', $html);
    }

    private function addSearch($search){
        $html[] = '<div class="search">';
        // title
        $html[] = '<div class="title">'.__('Search<br /> a stallion', 'horses-catalog').'</div>';
        //search
        $html[] = '<div>';
        // by letter
        $html[] = '<div class="letter">';
        $letters = 'abcdefghijklmnopqrstuvwxyz';
        foreach(str_split($letters) as $letter){
            $html[] = '<form method="get" >'; 
            $html[] = '<input type="hidden" name="start" value="'.$letter.'" />';
            $html[] = '<input type="submit" value="'.$letter.'" class="';
            if($search->start == $letter){
                $html[] = 'selected';
            }
            $html[] = '"/>';
            $html[] = '</form>';
        }

        $html[] = '</div>';
        // full
        $html[] = '<div class="by-name">';
        $html[] = '<form method="get" >';
        $html[] = '<label for="search-name" class="by-name-label">'.__('Search by name', 'horses-catalog').'</label>';
        $html[] = '<label for="search-name" class="by-stalion-label">'.__('Search a stallion', 'horses-catalog').'</label>';
        $html[] = '<input type="text" name="search" value="'.$search->name.'" />';
        $html[] = ' <input type="submit" value="'.__('OK', 'horses-catalog').'"/>';

        $html[] = '</form>';
        $html[] = '</div>';

        $html[] = '</div>';
        $html[] = '</div>';
        return $html;

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