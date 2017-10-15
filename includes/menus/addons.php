<?php	


/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 



class class_qa_addons{
	
	
    public function __construct(){
		
    }
	
	

	public function addons_data($addons_data = array()){
		
		$addons_data_new = array(
							

			'question-answer-email'=>array(	'title'=>'Question Aswer - Email',
										'version'=>'1.0.0',
										'price'=>'0',
										'content'=>'Get email notification when any action occurred via Question Answer plugin.',										
										'item_link'=>'https://wordpress.org/plugins/question-answer-email',
										'thumb'=>QA_PLUGIN_URL.'assets/admin/images/addons/question-answer-email.png',							
			),	

			

		);
		
		$addons_data = array_merge($addons_data_new,$addons_data);
		
		$addons_data = apply_filters('qa_filters_addons_data', $addons_data);
		
		return $addons_data;
		
		
		}



	public function qa_addons_html(){
		
		$html = '';
		
		$addons_data = $this->addons_data();
		
		foreach($addons_data as $key=>$values){
			
			$html.= '<div class="single '.$key.'">';
			$html.= '<div class="thumb"><a href="'.$values['item_link'].'"><img src="'.$values['thumb'].'" /></a></div>';			
			$html.= '<div class="title"><a href="'.$values['item_link'].'">'.$values['title'].'</a></div>';
			$html.= '<div class="content">'.$values['content'].'</div>';						
			$html.= '<div class="meta version"><b>'.__('Version:',PM_TEXTDOMAIN).'</b> '.$values['version'].'</div>';
			
			if($values['price']==0){
				
				$price = 'Free';
				}
			else{
				$price = '$'.$values['price'];
				
				}		
			$html.= '<div class="meta price"><b>'.__('Price:',PM_TEXTDOMAIN).'</b> '.$price.'</div>';							
			$html.= '<div class="meta download"><a href="'.$values['item_link'].'">'.__('Download',PM_TEXTDOMAIN).'</a></div>';				
			
			
			
			$html.= '</div>';
			
		
			
			}
		
		$html.= '';		
		
		return $html;
		}







}

new class_qa_addons();




	
	
?>





<div class="wrap">

	<div id="icon-tools" class="icon32"><br></div><?php echo "<h2>".__(QA_PLUGIN_NAME.' - Addons', PM_TEXTDOMAIN)."</h2>";?>

		<div class="qa-addons">
        
			<?php
            
            $class_qa_addons = new class_qa_addons();
            
            echo $class_qa_addons->qa_addons_html();
            
            
            ?>
        
        
        </div>

</div>
