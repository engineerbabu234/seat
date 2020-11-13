<form method="POST" id="question-logic" action="#">
	@csrf
	<div class="row">
		<div class="col-sm-12">
			<div id="question">
				<h5>Logic Builder</h5>
				<div class="row">
					<div class="col-sm-1"><div class="btn btn-warning choice" id="&&">&&</div></div>
					<div class="col-sm-1"><div class="btn btn-info choice" id="OR">OR</div></div>
					<div class="col-sm-1">	<div class="btn choice" style="background-color:#63339C" id="(">(</div></div>
					<div class="col-sm-1"><div class="btn btn-success choice"  id=")">)</div></div>
				</div>
				<div class="row">
					<div class="col-sm-6"><br>
						<fieldset>
							<h6>Overall Pass Logic</h6>
							<div id="answers">
								<div class="answerContainer emptyAnswerContainer ">&nbsp;</div>
							</div>
							<span class="error" id="logic_error"></span>
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
