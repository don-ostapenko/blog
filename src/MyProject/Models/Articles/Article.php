<?php

namespace MyProject\Models\Articles;

use MyProject\Models\ActiveRecordEntity;
use MyProject\Models\Users\User;
use MyProject\Exceptions\InvalidArgumentException;


class Article extends ActiveRecordEntity
{
    // Свойства
    /** @var string */
    protected $name;

    /** @var string */
    protected $text;

    /** @var string */
    protected $authorId;

    /** @var string */
    protected $createdAt;

    /** @var string */
    protected $imgName;


    // Сеттеры
    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }

    public function setAuthor(User $author)
    {
        $this->authorId = $author->getId();
    }

    public function setImgName(string $imgName)
    {
        $this->imgName = $imgName;
    }


    // Геттеры

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getImgName(): ?string
    {
        return $this->imgName;
    }

    protected static function getTableName(): string
    {
        return 'articles';
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return User::getById($this->authorId);
    }


    // Метод для прогонки текста через парсер markdown разметки
    public function getParsedText(): string
    {
        $parser = new \Parsedown();
        return $parser->text($this->getText());
    }


    // Метод для создания новой статьи
    public static function createFromArray(array $fields, User $author, array $filesArray): Article
    {
        $allowedExtensions = ['jpg'];

        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Название статьи не указано');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Текст статьи не указан');
        }

        if ($filesArray['img']['size'] > 1500000) {
            throw new InvalidArgumentException('Размер файла больше чем 1.5 мб');
        }

        if (!in_array(pathinfo($filesArray['img']['name'], PATHINFO_EXTENSION), $allowedExtensions)) {
            throw new InvalidArgumentException('Загрузка файлов с таким расширением запрещена!');
        }

        $article = new Article();

        $article->setAuthor($author);
        $article->setName($fields['name']);
        $article->setText($fields['text']);

        if ($filesArray['img']['error'] != 4) {
            $nameImg = $article->moveFileImg($filesArray);
            $article->setImgName($nameImg);
        }

        $article->save();
        return $article;
    }


    // Метод для редактирования статьи
    public function updateFromArray(array $fields, array $filesArray): Article
    {
        $allowedExtensions = ['jpg'];

        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано название статьи');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи');
        }

        if ($filesArray['img']['size'] > 1500000) {
            throw new InvalidArgumentException('Размер файла больше чем 1.5 мб');
        }

        if ((!in_array(pathinfo($filesArray['img']['name'], PATHINFO_EXTENSION), $allowedExtensions)) && $filesArray['img']['name'] !== '') {
            throw new InvalidArgumentException('Загрузка файлов с таким расширением запрещена!');
        }

        $this->setName($fields['name']);
        $this->setText($fields['text']);

        if ($filesArray['img']['error'] != 4) {
            $nameImgFromDb = $this->getImgName();
            if ($nameImgFromDb) {
                $filePath = __DIR__ . '/../../../../www/uploads/img/' . $nameImgFromDb . '.jpg';
                unlink($filePath);
            }
            $nameImg = $this->moveFileImg($filesArray);
            $this->setImgName($nameImg);
        }

        $this->save();
        return $this;
    }


    // Метод для обрабоки загружаемого файла (img)
    private function moveFileImg(array $file): string
    {
        $generatedNameImg = bin2hex(random_bytes(5)) . '-' . bin2hex(random_bytes(2)) . '-' . bin2hex(random_bytes(2)) . '-' . bin2hex(random_bytes(2)) . '-' . bin2hex(random_bytes(2));
        $newFilePath = __DIR__ . '/../../../../www/uploads/img/' . $generatedNameImg . '.jpg';
        move_uploaded_file($file['img']['tmp_name'], $newFilePath);

        return $generatedNameImg;
    }
}























