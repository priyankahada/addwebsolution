<?php
/**
 * The template for displaying Archive pages.
 *
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); 
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

?>

<div class="main-list">

<section class="resource-list">

	<div class="container">
		<form class="example" action="" >
            <div class="row">
                <div class="col-md-3">
					<div class="select-resource-type">
						<div class="form-group">
							<select name="resource_type[]" id="resource_type" multiple style="display:none;" >
								<?php

								    $resourcetypes = get_terms('resource_type');
									  foreach($resourcetypes as $resourcetype){

							    ?>

								<option <?php if($_GET['resource_type'] == $resourcetype->term_id){ echo 'selected="selected"'; } ?> value="<?php echo $resourcetype->term_id; ?>"><?php echo $resourcetype->name; ?></option>
						          
						        <?php } ?>
						            
						    </select>	
							<div class="resource-type-loader"><img class="loader_sec" src="<?php echo home_url(); ?>/wp-content/uploads/2022/11/loader.gif"></div>
						</div>
					</div>
									
				</div>
				
				<div class="col-md-3">
					<div class="select-resource-topic">
						<select name="resource_topic" id="resource_topic">
			            	<option value="">ANY TOPIC</option>
				            <?php
							    $resourcetopics = get_terms('resource_topic');
							    foreach($resourcetopics as $resourcetopic){
							?>

							<option <?php if($_GET['resource_topic'] == $resourcetopic->term_id){ echo 'selected="selected"'; } ?> value="<?php echo $resourcetopic->term_id; ?>"><?php echo $resourcetopic->name; ?></option>
				          
				            <?php } ?>

			        </select>
				  </div>
				</div>	

				<div class="col-md-5">
	               	<div class="resource-search-form">
						<input type="text" id="serachtitle" placeholder="Search" name="search2" value="<?php echo $_GET['search2']; ?>">
						<div id="suggesstion-box"></div>
                    
         			</div>
         			
				</div> 
				<div class="col-md-1">
					<span class="search-icon">
						<i class="fa fa-search customSearch"></i>
					</span>
				</div>
	  
            </div>
        </form>

		<div class="row" id="results">
		</div>		
	</div>

</section>	

</div>
		
<script>

jQuery('.customSearch').on("click",function(e){      
	let resource_topic = jQuery('#resource_topic').val();
	let resource_type = jQuery('#resource_type').val();
	if(resource_type.length == 0){
	resource_type = '';
	}

	let title = jQuery('#serachtitle').val();
    $.ajax({
         type : "POST",
         dataType : "html",
         url : "<?php echo admin_url('admin-ajax.php'); ?>",
         data : {action: "get_data",'title':title,'resource_type':resource_type,'resource_topic':resource_topic},
         success: function(response) {
         		jQuery('#results').html(response);
            }
        });   
  

});

jQuery(document).ready(function(){      

let resource_topic = "<?php echo (!empty($_GET['resource_topic'])) ? $_GET['resource_topic'] : ''; ?>";
let resource_type = '';
<?php
$resourcetypeParam = '';
if(!empty($_GET['resource_type'])){
    if(is_array($_GET['resource_type'])){
        $resourcetypeParam = json_encode($_GET['resource_type']);
        ?>
            resource_type = <?php echo $resourcetypeParam; ?>;
        <?php
    }else{
        $resourcetypeParam = $_GET['resource_type'];
        ?>
            resource_type = '<?php echo $resourcetypeParam; ?>';
        <?php
    }

}
?>
console.log('resource_type',resource_type);
let title = "<?php echo (!empty($_GET['search2'])) ? $_GET['search2'] : ''; ?>";
    $.ajax({
             type : "POST",
             dataType : "html",
             url : "<?php echo admin_url('admin-ajax.php'); ?>",
             data : {action: "get_data",'title':title,'resource_type':resource_type,'resource_topic':resource_topic},
             success: function(response) {
             		jQuery('#results').html(response);
             		
        jQuery('#resource_type').multiselect({
            buttonWidth: '155px',
            maxHeight:225,
          
		 	buttonText: function(options, select) {
		    if (options.length === 0) {
		        return 'ANY TYPE';
		    }
		    else if (options.length > 3) {
		        return 'More than 3 options selected!';
		    }
		    else {
		         var labels = [];
		         options.each(function() {
		             if ($(this).attr('label') !== undefined) {
		                 labels.push($(this).attr('label'));
		             }
		             else {
		                 labels.push($(this).html());
		             }
		         });

		         return labels.join(', ') + '';
		     }
		},
        

		 	onInitialized: function(select, container) {
    		jQuery('.resource-type-loader').hide();
			}

        });
            
            }
        });   
  

// AJAX call for autocomplete 

$(document).ready(function(){
	$("#serachtitle").keyup(function(){
		jQuery("#suggesstion-box").html('');
		 var current_length = $("#serachtitle").val().length;
		 if(current_length >0){
		 $.ajax({
            type : "POST",
            dataType : "html",
            url : "<?php echo admin_url('admin-ajax.php'); ?>",
            data : {action: "search_keyword", keyword:$(this).val()},
            success: function(response) {                
                	jQuery("#suggesstion-box").show();
						jQuery("#suggesstion-box").html(response);
						jQuery("#serachtitle").css("background","#FFF");
            }
        });
	}
	});
});
});

function selectresourcetype(val) {
	jQuery("#serachtitle").val(val);
	jQuery("#suggesstion-box").hide();
}

</script>

<style>
.resource-type-loader {
    text-align: center;
    width: 100%;
    height: 37px;
    position: absolute;
    background: #FFFFFF;
    top: 0;
    left: 0;
    z-index: 1;
}

.select-resource-type .resource-type-loader img {
    width: 25px;
    left: 45%;
    top: 6px;
    margin: 0 auto;
}	
	</style>

<?php get_footer(); ?>
