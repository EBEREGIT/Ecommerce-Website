
<!--nav-->
	<nav class="nav navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="nav-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
				<a href="/ecommerce_oop/admin/index.php" class="navbar-brand">Admin Ecommerce OOP</a>

			<div class="collapse navbar-collapse" id="collapse">
				<ul class="nav navbar-nav">
							
							<!--menu items-->
							<li><a href="brands.php">Brands</a></li>
							<li><a href="categories.php">Categories</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Products<span class="caret"></span></a>

								<ul class="dropdown-menu" role="menu">

									<li><a href="products.php">Avalable Products</a></li>
									<li><a href="products.php?archived">Archived Products</a></li>

								</ul>
							</li>

							<?php if (has_permission('admin')): ?>
								<li><a href="users.php">Users</a></li>
							<?php endif ?>
							
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?php echo $user_data['first']; ?> <span class="caret"></span></a>

								<ul class="dropdown-menu" role="menu">

									<li><a href="change_password.php">Change Password</a></li>
									<li><a href="logout.php">Logout</a></li>

								</ul>
							</li>
							<!-- <li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category']; ?> <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">								
										<li><a href="#"><?php echo $child['category']; ?></a></li>
								</ul>
							</li> -->
				</ul>
			</div>
		</div>
	</nav>
