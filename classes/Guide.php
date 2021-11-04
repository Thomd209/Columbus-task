<?php
declare(strict_types=1);

class Guide
{
    const PATH = 'public/uploads/';
    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    //Загружает файл
    public function upload(): void
    {
        $from = $_FILES['file']['tmp_name'];
        $to = self::PATH . $this->name;
        move_uploaded_file($from, $to);
    }

    //Добавляет колонку Error в файл
    public function addErrorColumn(): void
    {
        $handle = fopen(self::PATH . $this->name, 'r');
        while (($row = fgetcsv($handle, 1000)) !== false) {
            ($row[0] == 'Код') ? ($row[] = 'Error' AND $rows[] = $row) 
            : $rows[] = $row;
        }

        fclose($handle);

        $handle = fopen(self::PATH . $this->name, 'w');
        foreach ($rows as $row) {
            fputcsv($handle, $row, ',');
        }

        fclose($handle);
    }

    //Добавляет сообщение о недопустимых символах в поле Название в колонку Error
    public function addErrorValue(string $char): void
    {
        $handle = fopen(self::PATH . $this->name, 'r');
        while (($row = fgetcsv($handle, 1000)) !== false) {
            ($row[0] == '1') ? ($row[] = 'Недопустимый символ ' . $char 
            . ' в поле Название' AND $rows[] = $row) : $rows[] = $row;
        }

        fclose($handle);

        $handle = fopen(self::PATH . $this->name, 'w');
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
    }

    //Импорт данных из загруженного файла в БД
    public function importToDb(object $pdo): void 
    {
        $handle = fopen(self::PATH . $this->name, 'r');
        while (($row = fgetcsv($handle, 1000)) !== false) {
            if ($row[0] == 'Код') continue;

            $id = $row[0];
            $name = $row[1];

            $query = 'INSERT INTO guide (id, name) VALUES (:id, :name)';
            $stm = $pdo->prepare($query);
            $stm->bindValue(':id', $id);
            $stm->bindValue(':name', $name);
            $stm->execute();
        }

        fclose($handle);
    }

    //Обновление строки по колонке код
    public function updateString(object $pdo, int $id, string $name): void 
    {
        $query = 'UPDATE guide SET name = :name WHERE id = :id';
        $stm = $pdo->prepare($query);
        $stm->bindValue(':name', $name);
        $stm->bindValue(':id', $id);
        $stm->execute();
    }

    //Получение обновленной строки
    public function getUpdatedString(object $pdo, int $id): string
    {
        $query = 'SELECT name FROM guide WHERE id = :id';
        $stm = $pdo->prepare($query);
        $stm->bindValue(':id', $id);
        $stm->execute();

        return $stm->fetchAll()[0]['name'];
    }

    //Добавление обновленной строки в файл
    public function setUpdatedString(int $id, string $updatedName): void 
    {
        $handle = fopen(self::PATH . $this->name, 'r');
        while(($row = fgetcsv($handle, 1000)) !== false) {
            if ($row[0] == $id) $row[1] = $updatedName;
            $rows[] = $row;
        }

        fclose($handle);

        $handle = fopen(self::PATH . $this->name, 'w');
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
    }

    //Скачивание файла
    public function download(): void
    {
        $file = self::PATH . $this->name;

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            
            readfile($file);
            exit();
        }
    }
}