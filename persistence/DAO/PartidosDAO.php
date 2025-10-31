<?php
require_once 'GenericDAO.php';

class PartidosDAO extends GenericDAO {

    const TABLA_PARTIDOS = 'partidos';

    public function selectByJornada($jornada) {
        $stmt = $this->conn->prepare("SELECT * FROM " . PartidosDAO::TABLA_PARTIDOS . " WHERE jornada = ?");
        $stmt->bind_param("i", $jornada);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function insert($local, $visitante, $resultado, $jornada, $estadio) {
        // Validar que no se repitan partidos
        $check = $this->conn->prepare("SELECT * FROM " . PartidosDAO::TABLA_PARTIDOS . " WHERE local = ? AND visitante = ?");
        $check->bind_param("ii", $local, $visitante);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            return false; // Ya existe
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO " . PartidosDAO::TABLA_PARTIDOS . " (local, visitante, resultado, jornada, estadio) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("iisis", $local, $visitante, $resultado, $jornada, $estadio);
        return $stmt->execute();
    }

    public function obtenerJornadas() {
        $res = $this->conn->query("SELECT DISTINCT jornada FROM " . PartidosDAO::TABLA_PARTIDOS . " ORDER BY jornada");
        $jornadas = [];
        while ($row = $res->fetch_assoc()) $jornadas[] = (int)$row['jornada'];
        return $jornadas;
    }
}
