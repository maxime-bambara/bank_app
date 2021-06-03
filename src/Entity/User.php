<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;



/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Vich\Uploadable
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, Serializable
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="user_id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^(?=.*[0-9])(?=.*[A-Z]).{8,20}$/",
     * message="The password must be at least 8 characters long, but no more than 20 and contains at least one uppercase letter, one lowercase letter and one number")
     */
    private $password;


    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your last name must be at least {{ limit }} characters long",
     *      maxMessage = "Your last name cannot be longer than {{ limit }} characters"
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     * @Assert\Choice({"M.", "Mme"})
     * 
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $state = 'En cours';

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    private $birthday;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private $account = 3000;

    /**
     * @ORM\OneToMany(targetEntity=Beneficiary::class, mappedBy="sender", orphanRemoval=true)
     */
    private $beneficiaries;

    /**
     * @ORM\OneToMany(targetEntity=Transfert::class, mappedBy="sender", orphanRemoval=true)
     */
    private $transferts;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(
     *      min = 100000,
     *      max = 999999,
     *      notInRangeMessage = "Account id is not valid",
     * )
     */
    private $accountId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $idCardImg;

    /**
     * @Vich\UploadableField(mapping="file_id_card_img", fileNameProperty="idCardImg")
     * @var File
     */
    private $fileIdCardImg;

    /**
     * @ORM\Column(type="datetime",  options={"default": "CURRENT_TIMESTAMP"})
     * @var \DateTime
     */
    private $mis_a_jour_le;

    /**
     * @ORM\ManyToOne(targetEntity=Banker::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=false, name="banker_id" ,referencedColumnName="banker_id")
     */
    private $banker;




    public function __construct()
    {
        $this->beneficiaries = new ArrayCollection();
        $this->transferts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getAccount(): ?float
    {
        return $this->account;
    }

    public function setAccount(float $account): self
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return Collection|Beneficiary[]
     */
    public function getBeneficiaries(): Collection
    {
        return $this->beneficiaries;
    }

    public function addBeneficiary(Beneficiary $beneficiary): self
    {
        if (!$this->beneficiaries->contains($beneficiary)) {
            $this->beneficiaries[] = $beneficiary;
            $beneficiary->setSender($this);
        }

        return $this;
    }

    public function removeBeneficiary(Beneficiary $beneficiary): self
    {
        if ($this->beneficiaries->removeElement($beneficiary)) {
            // set the owning side to null (unless already changed)
            if ($beneficiary->getSender() === $this) {
                $beneficiary->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transfert[]
     */
    public function getTransferts(): Collection
    {
        return $this->transferts;
    }

    public function addTransfert(Transfert $transfert): self
    {
        if (!$this->transferts->contains($transfert)) {
            $this->transferts[] = $transfert;
            $transfert->setSender($this);
        }

        return $this;
    }

    public function removeTransfert(Transfert $transfert): self
    {
        if ($this->transferts->removeElement($transfert)) {
            // set the owning side to null (unless already changed)
            if ($transfert->getSender() === $this) {
                $transfert->setSender(null);
            }
        }

        return $this;
    }

    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    public function setAccountId(?int $accountId): self
    {

        $this->accountId = $accountId;

        return $this;
    }

    public function getIdCardImg(): ?string
    {
        return $this->idCardImg;
    }

    public function setIdCardImg(?string $idCardImg): self
    {
        $this->idCardImg = $idCardImg;

        return $this;
    }

    /**
     * @return null|File
     */
    public function getFileIdCardImg(): ?File
    {
        return $this->fileIdCardImg;
    }
    /**
     * @param File|null $fileIdCardImg
     */
    public function setFileIdCardImg(?File $fileIdCardImg = null): void
    {
        $this->fileIdCardImg = $fileIdCardImg;

        if ($fileIdCardImg) {
            $this->mis_a_jour_le = new \DateTime('now');
        }
    }

    public function serialize() {

        return serialize(array(
        $this->id,
        $this->email,
        $this->password,
        ));
        
     }
        
    public function unserialize($serialized) {
        
        list (
        $this->id,
        $this->email,
        $this->password,
        ) = unserialize($serialized);
    }

    public function checkAccountId(UserRepository $userRepository){
        $checkId = random_int(100000, 999999);
        if ($userRepository->findOneByAccountId($checkId) === null){
            return $checkId;
        }
        return $this->checkAccountId($userRepository);
    }

    public function getBanker(): ?Banker
    {
        return $this->banker;
    }

    public function setBanker(?Banker $banker): self
    {
        $this->banker = $banker;

        return $this;
    }


}
