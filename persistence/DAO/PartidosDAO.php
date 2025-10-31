<?php
require_once 'GenericDAO.php';

class PartidosDAO extends GenericDAO {

    const TABLA_PARTIDOS = 'partidos';

    public function selectByJornada($jornada) {
        $sql = "
            SELECT p.*, 
                   el.nombre AS local_nombre, 
                   ev.nombre AS visit_nombre
            FROM " . self::TABLA_PARTIDOS . " p
            JOIN equipos el ON p.idLocal = el.id
            JOIN equipos ev ON p.idVisitante = ev.id
            WHERE p.jornada = ?
            ORDER BY p.id
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $jornada);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Cambiado el orden y tipos de parámetros para coincidir con app/partidos.php
    public function insert($jornada, $local, $visitante, $estadio, $resultado) {
        // Validar que no se repitan partidos (misma jornada y mismos equipos)
        $check = $this->conn->prepare(
            "SELECT * FROM " . self::TABLA_PARTIDOS . " WHERE idLocal = ? AND idVisitante = ? AND jornada = ?"
        );
        $check->bind_param("iii", $local, $visitante, $jornada);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            return false; // Ya existe
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO " . self::TABLA_PARTIDOS . " (idLocal, idVisitante, estadio, resultado, jornada) VALUES (?, ?, ?, ?, ?)"
        );
        // tipos: i = int (local), i = int (visitante), s = string (estadio), s = string (resultado), i = int (jornada)
        $stmt->bind_param("iissi", $local, $visitante, $estadio, $resultado, $jornada);
        return $stmt->execute();
    }

    public function obtenerJornadas() {
        $res = $this->conn->query("SELECT DISTINCT jornada FROM " . self::TABLA_PARTIDOS . " ORDER BY jornada");
        $jornadas = [];
        while ($row = $res->fetch_assoc()) $jornadas[] = (int)$row['jornada'];
        return $jornadas;
    }

    public function selectByEquipo($equipo_id) {
    $stmt = $this->conn->prepare("
        SELECT p.*, 
               e1.nombre AS local_nombre, 
               e2.nombre AS visit_nombre
        FROM partidos p
        JOIN equipos e1 ON p.idLocal = e1.id
        JOIN equipos e2 ON p.idVisitante = e2.id
        WHERE p.idLocal = ? OR p.idVisitante = ?
        ORDER BY p.jornada
    ");
    $stmt->bind_param("ii", $equipo_id, $equipo_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
}