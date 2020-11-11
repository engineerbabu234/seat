 	<form method="POST" id="question-logic" action="#">
				@csrf
					<div class="row">

						<div class="col-sm-12">
								<div id="question">
							 <div class="custom-table-height">
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Sequance</th>
												<th>Text</th>
												<th>Possible Answers</th>
												<th>Correct Logic (PassLogic)</th>
											</tr>
										</thead>
										<tbody>
											@foreach($question as $key => $question)
											<tr>
												<td class="choice" id="{{$question->id}}">{{$question->id}}</td>
												<td>{{$question->question}}</td>
											    <td>Yes,No</td>
												<td>{{@$logic_ans[$question->correct_answer]}}</td>
											</tr> <!--end-->
											@endforeach
										</tbody>
									</table>
								</div>
							</div>


								<div class="row">
						       	<div class="col-sm-1"><div class="btn btn-warning choice" id="&&">&&</div></div>
						       	<div class="col-sm-1"><div class="btn btn-info choice" id="OR">OR</div></div>
						       	<div class="col-sm-1">	<div class="btn choice" style="background-color:#63339C" id="(">(</div></div>
						       	<div class="col-sm-1"><div class="btn btn-success choice"  id=")">)</div></div>
						       </div>
						   <div class="row">
						 	<div class="col-sm-6">
							    <fieldset>
							        <legend>Overall Pass Logic</legend>
							        <div id="answers">

							            <div class="answerContainer emptyAnswerContainer ">&nbsp;</div>

							        </div>
							    </fieldset>
								   </div>
								    	<div class="col-sm-6 text-right">
											<div class="add-product-btn  pt-5  ">
												<button class="btn btn-info save_question_logic" type="submit"> Save</button>
											</div>
										 </div>
								</div>
							</div>
						<div class="clearfix"></div>


						</div>


					 </div>



			</form>
