
function ReproducirMusica(titulo) { 
    console.log("Título de la canción",titulo);
     if (!titulo) { 
        alert('Por favor, ingresa el nombre de la canción.'); 
        return;
     } 
        const audioPlayer = document.getElementById('audioPlayer'); 
         if (!audioPlayer) { 
        alert('Audio Player no encontrado'); 
        return;
     } 
     audioPlayer.style.display = "block"; 
     //Aquí se hace la llamada al Controller
         const url = `./user/cancion/${encodeURIComponent(titulo)}`; 
         //const url = `{{ path('play_music', {'tituloCancion': '__titulo__'}) }}`.replace('__titulo__', encodeURIComponent(titulo));; 
         audioPlayer.src = url;

         audioPlayer.play().catch(err => {
        console.error('Error al reproducir la canción:', err); 
        alert('No se pudo reproducir la canción. Asegúrate de que el archivo existe.'); 
   });
   }
   async function mostrar_playlist(){
    console.log("prueba");
    let div = document.querySelector('#contenido_body');
    let query = await fetch(`/user/playlist`);
    let playlists = await query.json();

    div.innerHTML = `<h2>Tus Playlists</h2>`;
    //onclick="cargarPlaylist('${playlist.id}')"
    for (let playlist of playlists){
        div.innerHTML += `
            <div class="VistaPlaylist" >
            <img src="./img/playlist.png" alt="playlist" id="img_playlist" onclick="mostrar_canciones_playlist('${playlist.nombre}')">
                <h3>${playlist.nombre}</h3>
                <h4>Visibilidad: ${ playlist.visibilidad }</h4>
                <h4>Likes: ${playlist.likes}</h4>
                </div>`;
    }
   }
   
    async function mostrar_canciones(){
        console.log("prueba 2");
        let div = document.querySelector('#contenido_body');
        let query = await fetch(`/user/cancionesJSON`);
        let canciones = await query.json();
        div.innerHTML = `<h2>Tus Canciones</h2>`;
        //onclick="cargarPlaylist('${playlist.id}')"
        for (let cancion of canciones){
            div.innerHTML += `
                <div class="VistaCancion" onclick="ReproducirMusica('${cancion.titulo}')">
                <img src="./img/corchea.gif" alt="musica" id="img_cancion">
                    <h3>${cancion.titulo}</h3>
                    <h4>${ cancion.autor }</h4>
                </div>`;
        }
        div.innerHTML +=`
        <audio id="audioPlayer" controls style="display: block; margin-top: 20px;"> 
        Tu navegador no soporta el elemento de audio. </audio>`;
   }
   async function mostrar_canciones_playlist(tituloPlaylist){
    console.log("prueba 3");
    let div = document.querySelector('#contenido_body');
    let query = await fetch(`/user/CancionesPlaylist/${encodeURIComponent(tituloPlaylist)}`);
    let canciones = await query.json();
    div.innerHTML = `<h2>Las canciones de tu playlist</h2>`;
    //onclick="cargarPlaylist('${playlist.id}')"
    for (let cancion of canciones){
        div.innerHTML += `
            <div class="VistaCancion" onclick="ReproducirMusica('${cancion.titulo}')">
            <img src="./img/corchea.gif" alt="musica" id="img_cancion">
                <h3>${cancion.titulo}</h3>
                <h4>${ cancion.autor }</h4>
            </div>`;
    }
    div.innerHTML +=`
    <audio id="audioPlayer" controls style="display: block; margin-top: 20px;"> 
    Tu navegador no soporta el elemento de audio. </audio>`;
}
document.addEventListener("DOMContentLoaded", function() {
    let btn_buscar = document.querySelector("#buscarCancion");
    btn_buscar.addEventListener('click', buscarCancionXNombre);
});

async function buscarCancionXNombre(){
    console.log("prueba 4");
    let titulo = document.querySelector('#songName').value.trim();
    if(!titulo){
        alert("Por favor, ingresa el nombre de una canción");
    }
    let div = document.querySelector('#contenido_body');
    let query = await fetch(`/user/buscarCancion/${encodeURIComponent(titulo)}`);
    let canciones = await query.json();
    div.innerHTML = `<h2>Las canciones de tu búsqueda</h2>`;
    //onclick="cargarPlaylist('${playlist.id}')"
    for (let cancion of canciones){
        div.innerHTML += `
            <div class="VistaCancion" onclick="ReproducirMusica('${cancion.titulo}')">
            <img src="./img/corchea.gif" alt="musica" id="img_cancion">
                <h3>${cancion.titulo}</h3>
                <h4>${ cancion.autor }</h4>
            </div>`;
    }
    div.innerHTML +=`
    <audio id="audioPlayer" controls style="display: block; margin-top: 20px;"> 
    Tu navegador no soporta el elemento de audio. </audio>`;
}
