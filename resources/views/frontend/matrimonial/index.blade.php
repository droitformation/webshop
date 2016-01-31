@extends('layouts.matrimonial.master')
@section('content')

    <!-- Illustration -->
    <section id="photo"><img src=" {{ asset('/images/matrimonial/home.jpg') }}" alt=""></section>

    <div id="content" class="inner">
	    <div class="row">
			<div class="col-md-12">
				<h1>Droit matrimonial</h1>
				<p><strong>Se tenir informé en quelques clics</strong></p>
				<p>Ce site, lancé sous l'égide de la Faculté de droit de l'Université de Neuchâtel par les prof. François Bohnet et Olivier Guillod et Me Sabrina Burgat, docteure en droit, 
				est dédié aux nouveautés en droit matrimonial et aux décisions du Tribunal fédéral dans ce domaine. Il permet de prendre connaissance rapidement des derniers arrêts 
				rendus et de retrouver toute la jurisprudence en droit matrimonial depuis 2011, organisée par chapitres.</p>
				
				<h5 class="line">News</h5>
			</div>
		</div>		
    	<!-- arret block -->
		<div class="row">
			<div class="col-md-4">
				<h4>TF 5A_352/2013 - commenté par François Bohnet</h4>
				<p>L'auteur se penche sur la problématique des frais judiciaires et dépens en matière de divorce et vous propose une analyse de l’arrêt du Tribunal fédéral 5A_352/2013 du 22 août 2013. </p>
				<p><a href="">En savoir plus</a></p>
			</div>
			<div class="col-md-4">
				<h4>TF 5A_689/2012 - commenté par François Bohnet</h4>
				<p>Protection procédurale du créancier d’entretien ou d’aliments majeur. L'auteur vous propose une analyse de l’arrêt du Tribunal fédéral 5A_689/2012 du 3 juillet 2013.  </p>
				<p><a href="">En savoir plus</a></p>	
			</div>
			<div class="col-md-4">
				<h4>TF 5A_90/2013 - commenté par Bastien Durel</h4>
				<p>L'auteur propose une analyse de l'arrêt du Tribunal fédéral 5A_90/2013 du 3 avril 2013 et se penche sur les problématiques de la reconnaissance d'un droit de visite étranger, 
				du régime de l'article 85 LDIP et de l'intérêt de l'enfant. </p>
				<p><a href="">En savoir plus</a></p>
			</div>
		</div>
		
    </div>
		
@stop
