{extends file='page.tpl'}
{block name='page_header_container'}{/block}
{block name='page_content'}
{capture name=path}{l s='About Us'}{/capture}
<h1 class="h1 hidden">{l s="About Us"}</h1>
<div id="dor-about-content">
	<div class="row">
		<div class="about-us-v2-group-1" style="background-image:url({$urls.base_url}img/cms/dorado/aboutus/bg-main-v2-1.png);">
			<div class="container">
				<div class="about-us-v2-story">
					<div class="inner-about-story">
						<div class="info-story-main-middle">
							<div class="info-story-inner">
								<div class="story-info-main">
									<div class="story-info-content">
										<div class="story-head">
											<h2>{l s="Our Story"}</h2>
											<span>- {l s="Discover our beautiful farm"} -</span>
										</div>
										<div class="story-body">
											<p>We are <a href="#">Online Market</a> of organic fruits, vegetables, juices and dried fruits. Organic farming supports eco-sustenance, or farming in harmony with nature.</p>

											<p>Organic farming produces plant and animal foods without the excessive use of chemicals. It focuses on using fertile soil along with a variety of crops to maintain healthy growing conditions which produce a food with more nutrients and less chemicals than typical commercial foods.</p>

											<p>Visit our site for a complete list of exclusive we are stocking.</p>
										</div>
										<div class="story-footer">
											<span class="icon-line-author pull-left"><img src="{$urls.base_url}img/cms/dorado/aboutus/author-line.png" alt=""></span>
											<div class="info-footer-story pull-left">
												<h5>Jerry Hansen</h5>
												<span>/ {l s="Director Organic"}</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="story-image-top hidden-xs">
								<img src="{$urls.base_url}img/cms/dorado/aboutus/about-img-v2-1.jpg" alt="">
							</div>
							<div class="story-image-bottom hidden-xs">
								<img src="{$urls.base_url}img/cms/dorado/aboutus/about-img-v2-2.jpg" alt="">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<span class="about-line-middle"></span>
		<div class="about-us-v2-group-2">
			<div class="container">
				<div class="about-client-say">
					<h2>{l s="We love our clients!"}</h2>
					<div class="about-client-say-inner">
						<div class="client-say-signle col-lg-4 col-sm-4 col-xs-4">
							<div class="client-say-signle-wapper">
								<div class="client-say-content">
									<span class="icon-quot"></span>
									<div class="say-content">Organie store is the greatest online health food shop. I'm always going to be one.</div>
								</div>
								<span class="say-client-arrow"></span>
								<div class="say-client-author">
									<span class="pull-left"><img src="{$urls.base_url}img/cms/dorado/aboutus/test-persion-1.jpg" alt=""></span>
									<div class="say-client-author-info pull-left">
										<h3>Jerry Hansen</h3>
										<span>/ {l s="Director Organie"}</span>
									</div>
								</div>
							</div>
						</div>
						<div class="client-say-signle col-lg-4 col-sm-4 col-xs-4">
							<div class="client-say-signle-wapper">
								<div class="client-say-content">
									<span class="icon-quot"></span>
									<div class="say-content">I've been their loyal customer for years and I'm always going to be one.</div>
								</div>
								<span class="say-client-arrow"></span>
								<div class="say-client-author">
									<span class="pull-left"><img src="{$urls.base_url}img/cms/dorado/aboutus/test-persion-2.jpg" alt=""></span>
									<div class="say-client-author-info pull-left">
										<h3>Marry Isbister</h3>
										<span>/ {l s="Wifehouse"}</span>
									</div>
								</div>
							</div>
						</div>
						<div class="client-say-signle col-lg-4 col-sm-4 col-xs-4">
							<div class="client-say-signle-wapper">
								<div class="client-say-content">
									<span class="icon-quot"></span>
									<div class="say-content">Thank you for all the amazing products you deliver each week. I've been telling everyone about your great organic store!</div>
								</div>
								<span class="say-client-arrow"></span>
								<div class="say-client-author">
									<span class="pull-left"><img src="{$urls.base_url}img/cms/dorado/aboutus/test-persion-3.jpg" alt=""></span>
									<div class="say-client-author-info pull-left">
										<h3>Phillip Stone</h3>
										<span>/ {l s="Student"}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="about-us-group-4">
			<div class="container">
				<div class="row">
					<div class="about-brand-partner col-lg-12 col-sm-12 col-xs-12">
						<div class="aboutPartners">
							<div>
								<a href="#"><img src="{$urls.base_url}img/cms/dorado/aboutus/brands/brand-1.png" alt=""></a>
							</div>
							<div>
								<a href="#"><img src="{$urls.base_url}img/cms/dorado/aboutus/brands/brand-2.png" alt=""></a>
							</div>
							<div>
								<a href="#"><img src="{$urls.base_url}img/cms/dorado/aboutus/brands/brand-3.png" alt=""></a>
							</div>
							<div>
								<a href="#"><img src="{$urls.base_url}img/cms/dorado/aboutus/brands/brand-4.png" alt=""></a>
							</div>
							<div>
								<a href="#"><img src="{$urls.base_url}img/cms/dorado/aboutus/brands/brand-5.png" alt=""></a>
							</div>
							<div>
								<a href="#"><img src="{$urls.base_url}img/cms/dorado/aboutus/brands/brand-1.png" alt=""></a>
							</div>
							<div>
								<a href="#"><img src="{$urls.base_url}img/cms/dorado/aboutus/brands/brand-3.png" alt=""></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="about-us-group-5">
			<div class="container">
				<div class="row">
					<div class="about-our-farmers col-lg-12 col-sm-12 col-xs-12">
						<div class="ourfarmers-head">
							<h2>{l s="Our Farmers"}</h2>
							<span>- {l s="We are the best team"} -</span>
						</div>
						<div class="aboutus-ourfarmers">
							<div class="farmer-item">
								<img src="{$urls.base_url}img/cms/dorado/aboutus/farmer/persion-1.jpg" alt="">
								<h3>Tyler Palmer</h3>
								<span>{l s="Director"}</span>
							</div>
							<div class="farmer-item">
								<img src="{$urls.base_url}img/cms/dorado/aboutus/farmer/persion-2.jpg" alt="">
								<h3>Michael Andrews</h3>
								<span>{l s="Farmer"}</span>
							</div>
							<div class="farmer-item">
								<img src="{$urls.base_url}img/cms/dorado/aboutus/farmer/persion-3.jpg" alt="">
								<h3>Meghan Trainor</h3>
								<span>{l s="Farmer"}</span>
							</div>
							<div class="farmer-item">
								<img src="{$urls.base_url}img/cms/dorado/aboutus/farmer/persion-4.jpg" alt="">
								<h3>Mark Ronson</h3>
								<span>{l s="Farmer"}</span>
							</div>
							<div class="farmer-item">
								<img src="{$urls.base_url}img/cms/dorado/aboutus/farmer/persion-2.jpg" alt="">
								<h3>Tyler Smith</h3>
								<span>{l s="Farmer"}</span>
							</div>
							<div class="farmer-item">
								<img src="{$urls.base_url}img/cms/dorado/aboutus/farmer/persion-3.jpg" alt="">
								<h3>Maria Lee</h3>
								<span>{l s="Farmer"}</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

{/block}