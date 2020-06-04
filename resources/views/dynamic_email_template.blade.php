<style>
	.parrafo{
		margin-left: 1rem;
		margin-top: 0.5rem;
	}
	.titulo{
		margin-bottom: 0;
		font-size: 1.2rem;
		color: rgb(0, 42, 70);
	}
</style>
	<p>Se ha enviado el <span style="text-transform: lowercase;">{{ $data['title'] }}</span> número {{ $data['sub'] }} con los siguientes datos:</p>
	<h4 class="titulo">REMITENTE</h4>
	<p class="parrafo">{{$data['name']}} {{$data['surname']}} {{$data['second_surname']}}</p>
	<h4 class="titulo">DESTINATARIO</h4>
	<p class="parrafo">{{ $data['destinatary'] }}</p>
	<h4 class="titulo">CUERPO DEL {{ $data['title'] }}</h4>	
	<p class="parrafo"><b>Asunto:</b> {{$data['affair']}}</p>
	<p class="parrafo">Numero de Folios: {{$data['number_folios']}}</p>
	<p class="parrafo">Adjuntos: {{$data['attached']}}</p>