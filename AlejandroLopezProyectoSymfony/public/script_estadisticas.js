//se ejecuta cuando la página ha terminado de cargarse
document.addEventListener("DOMContentLoaded", function () {
  //hace una solicitud HTTP GET a la URL que devuelve la ruta generada por Symfony en (path('estadisticas_datos'))
  console.log("dentro de la función");
  //fetch("{{ path('estadisticas_datos') }}")
  fetch("manager/datos_reproducciones")
    .then((response) => response.json())
    .then((data) => {
      const labels = data.map((item) => item.playlist);
      const values = data.map((item) => item.totalReproducciones);
      const ctx = document
        .getElementById("reproduccionesChart")
        .getContext("2d");
      new Chart(ctx, {
        type: "bar",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Total Reproducciones",
              data: values,
              backgroundColor: "rgba(54, 162, 235, 0.5)",
              borderColor: "rgba(54, 162, 235, 1)",
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
      });
    });
});

document.addEventListener("DOMContentLoaded", function () {
  //hace una solicitud HTTP GET a la URL que devuelve la ruta generada por Symfony en (path('estadisticas_datos'))
  console.log("dentro de la función 2 ");
  //fetch("{{ path('likes_datos') }}")
  fetch("manager/datos_likes")
    .then((response) => response.json())
    .then((data) => {
      //Estos se tienen que llamar igual que los nombres del repositorio
      const labels = data.map((item) => item.playlist);
      const values = data.map((item) => item.totalLikes);
      const ctx = document.getElementById("LikesChart").getContext("2d");
      new Chart(ctx, {
        type: "bar",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Total Likes",
              data: values,
              backgroundColor: "rgba(4, 148, 40, 0.5)",
              borderColor: "rgb(54, 235, 130)",
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
      });
    });
});

document.addEventListener("DOMContentLoaded", function () {
  console.log("dentro de la función 3 ");
  //fetch("{{ path('canciones_reprod_datos') }}")
  fetch("manager/datos_reproducciones_cancion")
    .then((response) => response.json())
    .then((data) => {
      const labels = data.map((item) => item.cancion);
      const values = data.map((item) => item.reproduccionesXcancion); // Valores para el gráfico (total de reproducciones)

      const ctx = document
        .getElementById("reproduccionesCancionChart")
        .getContext("2d");

      new Chart(ctx, {
        type: "pie",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Total Reproducciones",
              data: values,
              backgroundColor: [
                "rgba(255, 99, 132, 0.5)",
                "rgba(54, 162, 235, 0.5)",
                "rgba(255, 206, 86, 0.5)",
                "rgba(75, 192, 192, 0.5)",
                "rgba(153, 102, 255, 0.5)",
                "rgba(255, 159, 64, 0.5)",
              ], // Colores para cada segmento
              borderColor: [
                "rgba(255, 99, 132, 1)",
                "rgba(54, 162, 235, 1)",
                "rgba(255, 206, 86, 1)",
                "rgba(75, 192, 192, 1)",
                "rgba(153, 102, 255, 1)",
                "rgba(255, 159, 64, 1)",
              ], // Colores de borde para cada segmento
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: "top", // Posición de la leyenda
            },
            tooltip: {
              callbacks: {
                label: function (tooltipItem) {
                  return (
                    tooltipItem.label +
                    ": " +
                    tooltipItem.raw +
                    " reproducciones"
                  ); // Personalización de la leyenda
                },
              },
            },
          },
        },
      });
    });
});

document.addEventListener("DOMContentLoaded", function () {
  console.log("dentro de la función 4 ");
  //fetch("{{ path('edad_datos') }}")
  fetch("manager/datos_edad")
    .then((response) => {
      console.log("Respuestas", response);
      if (response.ok) {
        return response.json();
      } else {
        throw new Error("Error al procesar datos");
      }
    })
    .then((data) => {
      let usuarios = data.reduce((acc, usuario) => {
        if (usuario.rango_edad) {
          acc[usuario.rango_edad] = acc[usuario.rango_edad] || [];
          acc[usuario.rango_edad].push(usuario);
        }
        return acc;
      }, {});
      const labels = Object.keys(usuarios);
      const values = Object.values(usuarios).map((group) => group.length);

      const ctx = document.getElementById("EdadesChart").getContext("2d");

      new Chart(ctx, {
        type: "pie",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Usuarios por edad",
              data: values,
              backgroundColor: [
                "rgba(255, 99, 132, 0.5)",
                "rgba(54, 162, 235, 0.5)",
                "rgba(255, 206, 86, 0.5)",
                "rgba(75, 192, 192, 0.5)",
                "rgba(153, 102, 255, 0.5)",
                "rgba(255, 159, 64, 0.5)",
              ], // Colores para cada segmento
              borderColor: [
                "rgba(255, 99, 132, 1)",
                "rgba(54, 162, 235, 1)",
                "rgba(255, 206, 86, 1)",
                "rgba(75, 192, 192, 1)",
                "rgba(153, 102, 255, 1)",
                "rgba(255, 159, 64, 1)",
              ], // Colores de borde para cada segmento
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: "top", // Posición de la leyenda
            },
            tooltip: {
              callbacks: {
                label: function (tooltipItem) {
                  return (
                    tooltipItem.label +
                    ": " +
                    tooltipItem.raw +
                    " reproducciones"
                  ); // Personalización de la leyenda
                },
              },
            },
          },
        },
      });
    });
});
