 	<form method="POST" id="edit-document-form"   name="edit-document-form" enctype="multipart/form-data" action="#">
				@csrf

				<div class="add-document">
					<div class="row">
							<input type="hidden" name="office_asset_id" id="office_asset_id" value="{{$document->office_asset_id}}">

						<div class="col-sm-12">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Document Title"  data-trigger="hover" data-content="Document Title" data-placement="left"><img src="{{asset('admin_assets')}}/images/title.png" class="icon bl-icon" width="25" ></span><span class="text-danger">*</span></h6>
							<input type="text" class="form-control" placeholder="Contract Document" name="document_title" id="document_title" value="{{ $document->document_title }}" >
							 <span class="error" id="edit_document_title_error"></span>
						</div>
						</div>
						<div class="col-sm-12">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Contract Document Name"  data-trigger="hover" data-content="Contract Document Name " data-placement="left"><img src="{{asset('admin_assets')}}/images/Name.png" class="icon bl-icon" width="25" ></span><span class="text-danger">*</span></h6>
							<a href="{{ $assets_path.'/'.$document->document_name }}" download>{{ $document->document_name }}</a>
							<br>
							<input type="file" class="form-control" placeholder="Contract Document File Name" name="document_name" id="document_name"  >
							 <span class="error" id="edit_document_name_error"></span>
						</div>
						</div>
						<div class="col-sm-12">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Document Description"  data-trigger="hover" data-content="Document Description" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="25" ></span><span class="text-danger">*</span></h6>
							 <textarea name="document_description" id="document_description" class="form-control">{{ $document->document_description }}</textarea>
							 <span class="error" id="edit_document_description_error"></span>
						</div>
						</div>

						<div class="col-sm-12">
						<div class="add-product-btn text-center">
							<button data-id="{{$document->id}}" class="btn btn-info edit_document" type="submit"> Update Document</button>
						</div>
					 </div>
					 </div>

				</div>


			</form>
