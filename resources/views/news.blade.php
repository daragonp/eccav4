@include('headernews')
        <hr>
		<div class="product-section mt-150 mb-150">
            <div class="container">
				<div class="row">
					<div class="col-lg-8 offset-lg-2 text-center">
						<div class="section-title">	
							<h3>Mensaje de la semana</h3>
							<p>Un análisis de temas de actualidad que seguramente, dejará un mensaje de esperanza para tí, querido lector.</p>
						</div>
					</div>
				</div>
                <div class="row">
                    @foreach($news as $post)                   
                        <div class="col-lg-4 col-md-6 text-center">
                            <div class="single-product-item">
                                <span>{{$post->created_at->format('d/M/Y')}}</span>
                                <div class="product-image"><br>
                                    <a href="{{url('showpost')}}"><img src="images/news/{{$post->image}}" alt="" height="200px;"></a><br>
                                </div>
                                <h3>{{$post->title}}</h3>
                                <p style="text-align: justify;">{!!strip_tags(Str::limit($post->abstract, 25))!!}</p>
                                <a href="{{url('showpost', $post->slug)}}" class="cart-btn"><i class="fa-solid fa-message"></i>Leer mas...</a>
                            </div>
                        </div>
                    @endforeach   
                </div>
            </div>
        </div>