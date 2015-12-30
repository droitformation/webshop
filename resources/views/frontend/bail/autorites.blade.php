@extends('layouts.bail.master')
@section('content')
		      				     
	 <!-- Illustration -->

	 <div id="content" class="inner">
	 	 <div class="row">
	 	 	<div class="large-12 columns">
		 	 	<h5 class="line">Instances cantonales en matière de bail</h5>
		 	 	<p>La présente page donne le lien vers les différentes autorités en matière de bail à loyer si celles-ci sont mentionnées par les sites cantonaux.</p>
	 	 	</div>
	 	 </div>
	     <div class="row">
	     
	        <div class="large-4 columns autorite-canton">
				<p><strong>Neuchâtel</strong><br/>
					<img src="{{ asset('/images/bail/canton/45fe078900.png') }}">
				</p>
				<p><a target="_blank" href="http://www.ne.ch/autorites/PJNE/tribunaux-regionaux/Pages/INST-conc.aspx">Autorités de conciliation</a><br/>
				<a target="_blank" href="http://www.ne.ch/autorites/PJNE/tribunaux-regionaux/Pages/INST-civ.aspx">Tribunaux</a></p>
	        </div>
	        
	        <div class="large-4 columns autorite-canton">
				<p><strong>Vaud</strong><br/>
				<img src="{{ asset('/images/bail/canton/aeef693a70.png') }}"></p>
				<p><a target="_blank" href="http://www.vd.ch/themes/territoire/districts-prefectures/commissions-prefectorales-de-conciliation/">
				Autorités de conciliation (Préfectures)</a><br/>
				<a target="_blank" href="http://www.vd.ch/autorites/ordre-judiciaire/tribunal-des-baux/">Tribunaux</a></p>
	        </div>
	        	        
	        <div class="large-4 columns autorite-canton">
				<p><strong>Genève</strong><br/>
				<img src="{{ asset('/images/bail/canton/0ca9bf9491.png') }}"></p>
				<p><a target="_blank" href="https://ge.ch/justice/commission-de-conciliation-en-matiere-de-baux-et-loyers">Autorité cantonale de conciliation</a><br/>
				<a target="_blank" href="https://ge.ch/justice/tribunal-des-baux-et-loyers">Tribunaux</a></p>
	        </div>
	   <div class="row">
	   </div>     
	        <div class="large-4 columns autorite-canton">
				<p><strong>Jura</strong><br/>
				<img src="{{ asset('/images/bail/canton/58b66c6d5e.png') }}"></p>
				<p><a target="_blank" href="http://www.jura.ch/JUST/Renseignements-juridiques/Droit-du-travail-et-du-bail.html">Autorités de conciliation (JU)</a><br/>
				<a target="_blank" href="http://www.jura.ch/JUST/Instances-judiciaires/Tribunal-de-premiere-instance/Tribunal-des-baux-a-loyer-et-a-ferme.html">Tribunaux (JU)</a></p>
	        </div>
	        
	        <div class="large-4 columns autorite-canton">
				<p><strong>Fribourg</strong><br/>
				<img src="{{ asset('/images/bail/canton/dfeb018409.png') }}"></p>
				<p><a target="_blank" href="http://www.fr.ch/pj/fr/pub/juridictions/organisation/bail.htm">Autorités de conciliation</a><br/>
				<a target="_blank" href="http://www.fr.ch/pj/fr/pub/juridictions/organisation/bail.htm">Tribunaux</a></p>
	        </div>	
	                	        
	        <div class="large-4 columns autorite-canton">
				<p><strong>Valais</strong><br/>
				<img src="{{ asset('/images/bail/canton/8aa7ed959b.png') }}"></p>
				<p><a target="_blank" href="https://www.vs.ch/Navig/navig.asp?MenuID=21513&Language=fr">Autorité cantonale de conciliation</a><br/>
				<a target="_blank" href="http://www.vs.ch/Navig/navig.asp?MenuID=25137">Tribunaux</a></p>
	        </div>
	   <div class="row">
	   </div>   
	        <div class="large-4 columns autorite-canton">
				<p><strong>Berne</strong><br/>
				<img src="{{ asset('/images/bail/canton/1a3d0e3117.png') }}"></p>
				<p><a target="_blank" href="http://www.justice.be.ch/justice/fr/index/justiz/organisation/obergericht/ueber_uns/schlichtungsbehoerden.html">
				Autorités de conciliation (BE)</a><br/>
				<a target="_blank" href="http://www.justice.be.ch/justice/fr/index/justiz/organisation/obergericht/ueber_uns/regionalgerichte.html">Tribunaux (BE)</a></p>
	        </div>
	        
	    </div>
	 </div>
@stop