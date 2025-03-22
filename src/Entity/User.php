<?php

namespace App\Entity;

use App\Enum\User\Locale;
use App\Enum\User\Theme;
use App\Repository\UserRepository;
use App\Service\UserNameService;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation\Loggable;
use Gedmo\Mapping\Annotation\SoftDeleteable;
use Gedmo\Mapping\Annotation\Versioned;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\HasLifecycleCallbacks]
#[Loggable]
#[SoftDeleteable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;
    use SoftDeleteableEntity;
    use BlameableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Versioned]
    public ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Versioned]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Versioned]
    public ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Versioned]
    public ?string $lastName = null {
        set {
            $this->lastName = strtoupper($value);
        }
        get => $this->lastName;
    }

    public string $fullName {
        get => sprintf('%s %s', $this->firstName, $this->lastName);
    }

    public string $initials {
        get => UserNameService::initials($this->firstName, $this->lastName);
    }

    #[ORM\Column(nullable: false, enumType: Locale::class)]
    #[Versioned]
    public Locale $preferredLocale = Locale::FR;

    #[ORM\Column(nullable: false, enumType: Theme::class)]
    #[Versioned]
    public Theme $preferredTheme = Theme::AUTO;

    public function __construct(?string $email, ?string $firstName, ?string $lastName)
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLoggable(): string
    {
        return sprintf(
            '[%s] %s %s (%s)',
            $this->id,
            $this->firstName,
            $this->lastName,
            $this->email,
        );
    }
}
