<?php
require_once 'GenericDAO.php';

class EquiposDAO extends GenericDAO {

    const TABLA_EQUIPOS = 'equipos';

    public function selectAll() {
        $query = "SELECT * FROM " . EquiposDAO::TABLA_EQUIPOS;  
        $result = $this->conn->query($query);
        $equipos = [];
        while ($row = $result->fetch_assoc()) {
            $equipos[] = $row;
        }
        return $equipos;
    }

    public function insert($nombre, $estadio) {
        $stmt = $this->conn->prepare("INSERT INTO " . EquiposDAO::TABLA_EQUIPOS . " (nombre, estadio) VALUES (?, ?)"); 
        $stmt->bind_param("ss", $nombre, $estadio);
        return $stmt->execute();
    }

    public function selectById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM " . EquiposDAO::TABLA_EQUIPOS . " WHERE id = ?"); 
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}