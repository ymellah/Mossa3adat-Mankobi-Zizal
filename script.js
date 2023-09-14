$(document).ready(function () {

    function loadLocalisations() {
        $.get("api.php", function (data) {
            var localisations = data.data;
            var table = $("#localisationTable");
            table.empty();

            localisations.forEach(function (localisation) {
                var visiteText = "";
				console.log(localisation.visite);
if (localisation.visite === '1') {
	
    visiteText = "نعم";
} 
if (localisation.visite === '0') {
    visiteText = "لا";
}
                var row = $("<tr>");
                row.append("<td>" + localisation.nom + "</td>");
                row.append("<td>" + visiteText + "</td>");
                row.append("<td><button class='btn btn-primary btn-modifier' data-id='" + localisation.id + "' data-visite='" + localisation.visite + "'>تعديل</button></td>");
                table.append(row);
            });
        });
    }

    loadLocalisations();

    $("#ajouterForm").submit(function (e) {
        e.preventDefault();
        var nom = $("#nom").val();

        $.post("api.php", { nom: nom }, function (data) {
            alert(data.message);
            $("#nom").val("");
            loadLocalisations();
        }).fail(function (xhr) {
            alert(xhr.responseJSON.error);
        });
    });

    // Utiliser un gestionnaire d'événements jQuery pour le bouton "Modifier"
    $(document).on("click", ".btn-modifier", function () {
        var id = $(this).data("id");
        var visite = $(this).data("visite");

        var nouvelleVisite = visite === 1 ? 0 : 1;

        $.ajax({
            url: "api.php",
            type: "PUT",
            data: { id: id, visite: nouvelleVisite },
            success: function (data) {
                alert(data.message);
                loadLocalisations();
            },
            error: function (xhr) {
                alert(xhr.responseJSON.error);
            }
        });
    });
});
