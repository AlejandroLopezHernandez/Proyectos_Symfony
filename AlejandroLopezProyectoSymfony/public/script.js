function ReproducirMusica(titulo) {
  console.log("Título de la canción", titulo);
  if (!titulo) {
    alert("Por favor, ingresa el nombre de la canción.");
    return;
  }
  const audioPlayer = document.getElementById("audioPlayer");
  if (!audioPlayer) {
    alert("Audio Player no encontrado");
    return;
  }
  audioPlayer.style.display = "block";
  //Aquí se hace la llamada al Controller
  const url = `./user/cancion/${encodeURIComponent(titulo)}`;
  //const url = `{{ path('play_music', {'tituloCancion': '__titulo__'}) }}`.replace('__titulo__', encodeURIComponent(titulo));;
  audioPlayer.src = url;

  audioPlayer.play().catch((err) => {
    console.error("Error al reproducir la canción:", err);
    alert(
      "No se pudo reproducir la canción. Asegúrate de que el archivo existe."
    );
  });
}
async function mostrar_playlist() {
  console.log("prueba");
  let div = document.querySelector("#contenido_body");
  let query = await fetch(`/user/playlist`);
  let playlists = await query.json();

  div.innerHTML = `<h2>Tus Playlists</h2>`;
  //onclick="cargarPlaylist('${playlist.id}')"
  let grid = document.createElement("div");
  grid.classList.add("row", "grid-playlist");
  for (let playlist of playlists) {
    let playlistHTML = `
          <div class="col-md-3 col-sm-6 col-12">
            <div class="VistaPlaylist" >
            <img src="./img/playlist.jpg" alt="playlist" id="img_playlist" onclick="mostrar_canciones_playlist('${playlist.nombre}')">
                <h4>${playlist.nombre}</h4>
                <h5>Likes: ${playlist.likes}</h5>
                </div>
                </div>
                </div>`;
    grid.innerHTML += playlistHTML;
  }
  div.appendChild(grid);
}

async function mostrar_canciones() {
  console.log("prueba 2");
  let div = document.querySelector("#contenido_body");
  let query = await fetch(`/user/cancionesJSON`);
  let canciones = await query.json();
  div.innerHTML = `<h2>Tus Canciones</h2>`;
  //onclick="cargarPlaylist('${playlist.id}')"
  let grid = document.createElement("div");
  grid.classList.add("row", "grid-cancion");
  for (let cancion of canciones) {
    let cancionHTML = `
          <div class="col-md-3 col-sm-6 col-12">
                <div class="VistaCancion" onclick="ReproducirMusica('${cancion.titulo}')">
                <img src="./img/corchea.gif" alt="musica" id="img_corchea">
                    <h4>${cancion.titulo}</h4>
                    <h5>${cancion.autor}</h5>
                </div>
                </div>
                </div>`;
    grid.innerHTML += cancionHTML;
  }
  div.appendChild(grid);
  div.innerHTML += `
        <audio id="audioPlayer" controls style="display: block; margin-top: 20px;"> 
        Tu navegador no soporta el elemento de audio. </audio>`;
}
async function mostrar_canciones_playlist(tituloPlaylist) {
  console.log("prueba 3");
  let div = document.querySelector("#contenido_body");
  let query = await fetch(
    `/user/CancionesPlaylist/${encodeURIComponent(tituloPlaylist)}`
  );
  let canciones = await query.json();
  div.innerHTML = `<h2>Las canciones de tu playlist</h2>`;
  //onclick="cargarPlaylist('${playlist.id}')"
  let grid = document.createElement("div");
  grid.classList.add("row", "grid-cancion");
  for (let cancion of canciones) {
    let cancionHTML = `
          <div class="col-md-3 col-sm-6 col-12">
            <div class="VistaCancion" onclick="ReproducirMusica('${cancion.titulo}')">
            <img src="./img/corchea.gif" alt="musica" id="img_corchea">
                <h4>${cancion.titulo}</h4>
                <h5>${cancion.autor}</h5>
            </div>
            </div>
                </div>`;
    grid.innerHTML += cancionHTML;
  }
  div.appendChild(grid);
  div.innerHTML += `
    <audio id="audioPlayer" controls style="display: block; margin-top: 20px;"> 
    Tu navegador no soporta el elemento de audio. </audio>`;
}
// document.addEventListener("DOMContentLoaded", function() {
//     let btn_buscar = document.querySelector("#buscarCancion");
//     btn_buscar.addEventListener('click', buscarCancionXNombre);
// });
let btn_buscar = document.querySelector("#buscarCancion");
btn_buscar.addEventListener("click", buscarCancionesYplaylist);

async function buscarCancionesYplaylist() {
  console.log("Buscando canciones y playlists...");
  let titulo = document.querySelector("#songName").value.trim();
  if (!titulo) {
    alert("Por favor, ingresa el nombre de una canción");
  }
  let div = document.querySelector("#contenido_body");
  div.innerHTML = `<h2>Resultados de tu búsqueda</h2>`;
  //Buscar Canciones
  try {
    let queryCanciones = await fetch(
      `/user/buscarCancion/${encodeURIComponent(titulo)}`
    );
    let canciones = await queryCanciones.json();
    //onclick="cargarPlaylist('${playlist.id}')"
    if (Array.isArray(canciones) && canciones.length > 0) {
      for (let cancion of canciones) {
        div.innerHTML += `
                    <div class="VistaCancion" onclick="ReproducirMusica('${cancion.titulo}')">
                    <img src="./img/corchea.gif" alt="musica" id="img_corchea">
                        <h4>${cancion.titulo}</h4>
                        <h5>${cancion.autor}</h5>
                    </div>`;
      }
    } else {
      div.innerHTML += `<p>No se encontraron canciones</p>`;
    }
  } catch (error) {
    console.error(error);
    div.innerHTML += `<p>Error al buscar canciones</p>`;
  }
  //Buscar Playlist
  try {
    let queryPlaylist = await fetch(
      `user/buscarPlaylist/${encodeURIComponent(titulo)}`
    );
    let playlists = await queryPlaylist.json();
    //onclick="cargarPlaylist('${playlist.id}')"
    if (Array.isArray(playlists) && playlists.length > 0) {
      for (let playlist of playlists) {
        div.innerHTML += `
                    <div class="VistaPlaylist" onclick="mostrar_canciones_playlist('${playlist.nombre}')">
                    <img src="./img/playlist.jpg" alt="musica" id="img_corchea">
                        <h4>${playlist.nombre}</h4>
                        <h5>${playlist.likes}</h5>
                    </div>`;
      }
    } else {
      div.innerHTML += `<p>No se encontraron playlists</p>`;
    }
  } catch {
    console.error(error);
    div.innerHTML += `<p>Error al buscar canciones</p>`;
  }
  div.innerHTML += `
    <audio id="audioPlayer" controls style="display: block; margin-top: 20px;"> 
    Tu navegador no soporta el elemento de audio. </audio>`;
}
