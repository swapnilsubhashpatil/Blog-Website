<?php
   include 'header.php';
   $blogID= $_GET['id'];
   if(empty($blogID)){
      header("location:index.php");
   }
   
  if(isset($_SESSION['user_data'])){
    $author_id = $_SESSION['user_data']['2'];
   }

   //fetch categories
   $sql = "SELECT * FROM categories";
   $query=mysqli_query($config,$sql);
   //GET BLOG ID
   $sql2 ="SELECT * FROM blog LEFT JOIN categories ON blog.category=categories.cat_id LEFT JOIN user ON blog.author_id=user.user_id WHERE blog_id='$blogID'";
   $query2 = mysqli_query($config,$sql2);
   $result=mysqli_fetch_assoc($query2);
?>

<div class="container-fluid mb-4">
   <h5 class="mb-2 text-gray-800">Blogs</h5>
   <div class="row">
      <div class="col-xl-12 col-lg-6">
         <div class="card">
            <div class="card-header">
               <h6 class="font-weight-bold text-primary mt-2">Edit blogs</h6>
            </div>
            <div class="card-body">
               <form action="" method="POST" enctype="multipart/form-data">
                  <div class="p-4">
                     <div class="mb-3">

                     <!-- To display the input  we use value -->
                       <input type="text" name="blog_title" class="form-control" placeholder="Title" required value="<?=$result['blog_title'];?>">
                     </div>
                     <div class="mb-3">
                        <label>Body</label>
                        <textarea class="form-control" name="blog_body" id="blog" rows="2" required>
                        <?=$result['blog_body'];?>
                        </textarea>
                     </div>
                     <div class="mb-3">
                        <input type="file" name="blog_image" class="form-control" >
                        <img src="upload/".<?=$result['image'];?> width="100px">
                     </div>
                     <div class="mb-3">
                        <select name="category" class="form-control" required>
                           
                           <?php while($cats=mysqli_fetch_assoc($query)){?>
                           <option value="<?= $cats['cat_id']; ?>"
                            <?php 
                              if($result['category']==$cats['cat_id']){
                                echo "selected";
                              }else{
                                echo "";
                              }
                            ?>>
                            <?= $cats['cat_name']; ?>
                        </option>
                           <?php
                              }?>
                        </select>
                     </div>
                     <div class="mb-3">
                        <input type="submit" name="edit_blog" value="Update" class="btn btn-primary">
                        <a href="index.php" class="btn btn-secondary">Back</a>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<?php include 'footer.php';
     if(isset($_POST['edit_blog'])){
      $title = $_POST['blog_title'];
      $body = $_POST['blog_body'];
      $category = $_POST['category'];
      $filename = $_FILES['blog_image']['name'];
      $tmp_name = $_FILES['blog_image']['tmp_name'];
      $size = $_FILES['blog_image']['size'];
      $image_ext = strtolower(pathinfo($filename ,PATHINFO_EXTENSION));
      $allow_type = ['jpg','png','jpeg'];
      $destination ="upload/".$filename;
      if(!empty($filename)){
        if(in_array($image_ext, $allow_type)){
            if($size <= 2000000){
                $unlink="upload/".$result['image'];
                unlink($unlink);
                move_uploaded_file($tmp_name, $destination);
                $sql3 = "UPDATE blog SET blog_title='$title',blog_body='$body',image='$filename',category='$category',author_id='$author_id' WHERE blog_id='$blogID'";
                $query3 = mysqli_query($config, $sql3);
                if($query3){
                    $msg = ['Post updated Successfully !!', 'alert-success'];
                    $_SESSION['msg'] = $msg;
                    header("location:index.php");
                    exit();
                }
            } 
            else {  
                $msg = ['Image size should not be greater than 2mb!!', 'alert-danger'];
                $_SESSION['msg'] = $msg;
                header("location:index.php");
                exit();
            }
        } 
        else {  
            $msg = ['File type is not allowed !!(only jpg, png and jpeg)', 'alert-danger'];
            $_SESSION['msg'] = $msg;
            header("location:index.php");
            exit();
        }
        }
        else{
            $sql3 = "UPDATE blog SET blog_title='$title',blog_body='$body',category='$category',author_id='$author_id' WHERE blog_id='$blogID'";
                $query3 = mysqli_query($config, $sql3);
                if($query3){
                    $msg = ['Post updated Successfully !!', 'alert-success'];
                    $_SESSION['msg'] = $msg;
                    header("location:index.php");
                    exit();
                }
                else {  
                    $msg = ['Failed', 'alert-danger'];
                    $_SESSION['msg'] = $msg;
                    header("location:index.php");
                    exit();
                }
            } 
            
        }
    ?>
