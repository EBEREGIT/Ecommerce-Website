<?php 
	$sql = "SELECT * FROM categories WHERE parent = 0";
	$parent_query = $db->query($sql);
 ?>
<!--nav-->
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="nav-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<a href="index.php" class="navbar-brand">Ecommerce OOP</a>

			<div class="collapse navbar-collapse" id="collapse">
				<ul class="nav navbar-nav">

					<?php while ($parent = mysqli_fetch_assoc($parent_query)) : ?>

						<?php 
							$parent_id = $parent['id'];
							$sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
							$child_query = $db->query($sql2);
						?>
							
							<!--menu items-->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category']; ?> <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">

									<?php while ($child = mysqli_fetch_assoc($child_query)) : ?>
									
										<li><a href="category.php?cat=<?php echo $child['id']; ?>"><?php echo $child['category']; ?></a></li>

									<?php endwhile; ?>

								</ul>
							</li>

					<?php endwhile; ?>
				</ul>
			</div>
		</div>
	</nav>
