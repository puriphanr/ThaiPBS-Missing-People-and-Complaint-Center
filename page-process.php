<?php 
/*
Template name: process
*/
date_default_timezone_set('Asia/Bangkok');
function getMissing(){
	$id = $_GET['id'];
		?>
		<div class="col-lg-12 missing-blog">
			<div class="col-lg-4">
				<div class="image">
					<img src="<?php echo get_field('image',$id)['sizes']['large'] ?>"  class="img-responsive thumbnail"/>
				</div>
			</div>
			<div class="col-lg-8">
				<h2>ข้อมูลส่วนตัว</h2>
				 <table class="table table-striped">
					<tbody>
						<tr>
							<th width="20%">ชื่อ - นามสกุล</th>
							<td width="80%"><?php echo get_field('fname',$id)?> <?php echo get_field('lname',$id)?></td>
						</tr>
						<tr>
							<th>ชื่อเล่น</th>
							<td><?php echo get_field('nickname',$id)?></td>
						</tr>
						<tr>
							<th>อายุ</th>
							<td><?php echo get_field('age',$id)?> ปี</td>
						</tr>
						<tr>
							<th>จังหวัด</th>
							<td><?php echo get_field('province',$id)?></td>
						</tr>
						<tr>
							<th>วันที่หาย</th>
							<td><?php echo DateThai(get_field('missing_date',$id))?></td>
						</tr>
						<tr>
							<th>ข้อมูลพบเห็นล่าสุด</th>
							<td><?php echo nl2br(get_field('last_seeing',$id))?></td>
						</tr>
						<tr>
							<th>ข้อมูลเพิ่มเติม</th>
							<td><?php echo nl2br(get_field('other_description',$id))?></td>
						</tr>
					</tbody>
				 </table>
				
			</div>
		</div>
		<?php
		
}

function save_data(){
	$type = $_POST['p_type'];
	
	if($type == 'missing_people'){
	
		foreach($_FILES['acf'] as $key=>$row){
			$_FILES['upload'][$key] = $row['field_599d7cc76043b'];
		}

		$my_post = array(
			'post_title'	=> postCode($type),
			'post_type'		=> $type,
			'post_status'	=> 'draft'
		);
		$post_id = wp_insert_post( $my_post );
		if(!empty($post_id)){
			update_field('field_59a8ebd47778e', 1 , $post_id);
			update_field('field_59969e8bafd9c', 5 , $post_id);
			update_field('field_59a520ba4fdb6', 1 , $post_id);
			foreach($_POST['acf'] as $key=>$row){
				if($key == 'field_599d7cc76043b'){
					$att = my_update_attachment('upload',$post_id);
					update_field( 'field_599d7cc76043b', $att['attach_id'] , $post_id );
				}
				else{
					update_field( $key , $row , $post_id );
				}
				
			}
			sendMail($type ,$post_id);
			$result['status'] = true;
			$result['id'] = $post_id;
		}
		else{
			$result['status'] = false;
			$result['error'] = 'ไม่สามารถบันทึกข้อมูลได้';
		}
	}
	else{
		foreach($_FILES['acf'] as $key=>$row){
			$_FILES['upload'][$key] = $row['field_59c23ef0861f9'];
		}
		
		$my_post = array(
			'post_title'	=> postCode($type),
			'post_type'		=> $type,
			'post_status'	=> 'draft'
		);
		$post_id = wp_insert_post( $my_post );
		if(!empty($post_id)){
			
			if($type == 'inform'){
				update_field('field_599fcbd60bd0a', 1 , $post_id);
				update_field('field_599fcb7200df2', 5 , $post_id);
				update_field('field_59a5211bd0014', 1 , $post_id);
			}
			else{
				update_field('field_59a000bcc32fc', 1 , $post_id);
				update_field('field_59a000bcc3826', 5 , $post_id);
				update_field('field_59a520689e725', 1 , $post_id);
			}
			
			foreach($_POST['acf'] as $key=>$row){
				if($key == 'field_59c23ef0861f9'){
					$att = upload_file('upload',$post_id);
					update_field( 'field_59c23ef0861f9', $att['attach_id'] , $post_id );
				}
				else{
					update_field( $key , $row , $post_id );
				}
				
			}
			sendMail($type ,$post_id);
			$result['status'] = true;
			$result['id'] = $post_id;
		}
		else{
			$result['status'] = false;
			$result['error'] = 'ไม่สามารถบันทึกข้อมูลได้';
		}
	}
	
	echo json_encode($result);
}

function downloadImage(){
	$file= get_field('image',$_GET['post']);
	$filePath = get_attached_file($file['id']);
	
	
    if(file_exists($filePath)) {
        $fileName = basename($filePath);
        $fileSize = filesize($filePath);

        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: ".$fileSize);
        header("Content-Disposition: attachment; filename=".$fileName);

        // Output file.
        readfile ($filePath);                   
        exit();
    }
    else {
        die('The provided file path is not valid.');
    }
}

if($_GET['fn']){
	$_GET['fn']();
}
?>
