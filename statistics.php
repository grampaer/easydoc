<h2>Nombre d’entrées par mois (année courante)</h2>
    <canvas id="myChart"></canvas>

     <script>
     fetch("data.php", {
             credentials: "include"
                 })
     .then(response => response.json())
     .then(data => {
             const ctx = document.getElementById("myChart").getContext("2d");
             new Chart(ctx, {
                     type: "bar",
                         data: {
                         labels: data.labels,
                             datasets: [{
                                 label: "Entrées",
                                     data: data.counts,
                                     backgroundColor: "#42a5f5"
                                     }]
                     },
                         options: {
                         scales: {
                             y: { beginAtZero: true },
                                 x: { title: { display: true, text: "Mois" } }
                         }
                     }
             });
         });
</script>
