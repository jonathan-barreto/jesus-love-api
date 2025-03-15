<?php

namespace App\Console\Commands;

use App\Models\Address;
use App\Models\Photo;
use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserAccount;
use App\Models\UserDenomination;
use App\Models\UserInterest;
use App\Models\UserPersonalDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GenerateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera 10 usuários masculinos e 10 femininos com nomes brasileiros';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Gerando usuários brasileiros...');

        // Listas de nomes brasileiros
        $maleNames = [
            "Jonathan Aguiar Barreto",
            "Pedro Henrique",
            "José Roberto",
            "Fernando Luiz",
            "Lucas Gabriel",
            "Marcelo Augusto",
            "Thiago Almeida",
            "Ricardo Silva",
            "André Souza",
            "Gustavo Henrique",
            "Bruno Oliveira",
            "Renato Farias",
            "Rodrigo Mendes",
            "Diego Costa",
            "Felipe Ramos",
            "Vinícius Matos",
            "Leonardo Teixeira",
            "Eduardo Lima",
            "João Vitor",
            "Rafael Alves"
        ];

        $femaleNames = [
            "Ana Carolina",
            "Mariana Souza",
            "Beatriz Oliveira",
            "Fernanda Mendes",
            "Juliana Lima",
            "Gabriela Santos",
            "Camila Rocha",
            "Tatiane Costa",
            "Aline Figueiredo",
            "Isabela Fernandes",
            "Larissa Teixeira",
            "Patrícia Gomes",
            "Amanda Ribeiro",
            "Natália Duarte",
            "Cristiane Alves",
            "Renata Barros",
            "Letícia Nunes",
            "Viviane Martins",
            "Bruna Andrade",
            "Daniela Castro"
        ];

        // Embaralha e seleciona 10 nomes aleatórios sem repetição
        shuffle($maleNames);
        shuffle($femaleNames);

        $selectedMaleNames = array_slice($maleNames, 0, 10);
        $selectedFemaleNames = array_slice($femaleNames, 0, 10);

        $genders = [
            'male' => $selectedMaleNames,
            'female' => $selectedFemaleNames
        ];

        foreach ($genders as $gender => $names) {
            foreach ($names as $name) {
                $firstName = explode(" ", $name)[0];
                $email = strtolower(str_replace(' ', '', $firstName)) . '@teste.com';

                $user = User::create([
                    'name' => $name,
                    'email' => strtolower(preg_replace('/[^a-zA-Z0-9]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', explode(" ", $name)[0]))) . '@teste.com',
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ]);


                UserProfile::create([
                    'user_id' => $user->id,
                    'phone_number' => "+55 21 9" . fake()->numerify("####-####"),
                    'date_of_birth' => fake()->date(),
                    'gender' => $gender,
                    'bio' => $this->generateBio($gender),
                ]);

                Address::create([
                    'user_id' => $user->id,
                    'state' => 'RJ',
                    'city' => fake()->randomElement([
                        'Belford Roxo',
                        'Rio de Janeiro',
                        'Niterói',
                        'Duque de Caxias',
                        'Nova Iguaçu',
                    ]),
                    'lat' => fake()->randomFloat(6, -23.0, -22.0),
                    'long' => fake()->randomFloat(6, -44.0, -42.0),
                ]);

                UserDenomination::create([
                    'user_id' => $user->id,
                    'denomination_id' => rand(1, 12),
                ]);

                UserInterest::create([
                    'user_id' => $user->id,
                    'interest_id' => rand(1, 22),
                ]);

                for ($index = 0; $index < 4; $index++) {
                    Photo::create([
                        'user_id' => $user->id,
                        'photo_name' => $gender == 'male' ? '2148171583.jpg' : '42405.jpg',
                    ]);
                }

                UserAccount::create([
                    'user_id' => $user->id,
                    'is_active' => true,
                    'accepted_terms' => true,
                    'is_subscriber' => fake()->boolean(20), // 20% de chance de ser assinante
                    'visibility' => true,
                    'last_login' => now(),
                    'device_token' => Str::random(60),
                ]);

                UserPersonalDetail::create([
                    'user_id' => $user->id,
                    'marital_status_id' => rand(1, 3),
                    'children_preference_id' => rand(1, 3),
                    'education_id' => rand(1, 3),
                ]);
            }
        }

        $this->info('20 usuários brasileiros gerados com sucesso!');
    }

    /**
     * Gera uma biografia aleatória em português.
     */
    private function generateBio($gender)
    {
        $bios = [
            'male' => [
                "Apaixonado por tecnologia e café.",
                "Cristão buscando propósito e conexões reais.",
                "Amante da música e dos bons livros.",
                "Gosto de viajar e conhecer novas culturas.",
                "Entusiasta de esportes e vida saudável.",
                "Acredito que pequenos gestos fazem a diferença.",
                "Sempre em busca de aprender algo novo.",
                "Cristão temente a Deus e fiel aos princípios.",
                "Família e amigos são essenciais para mim.",
                "Buscando alguém para compartilhar a vida."
            ],
            'female' => [
                "Sonhadora e cheia de fé em Deus.",
                "Amante da natureza e da tranquilidade.",
                "Cristã que valoriza conexões genuínas.",
                "Adoro cozinhar e passar tempo com a família.",
                "A música é parte essencial da minha vida.",
                "Buscando um parceiro para uma jornada de fé.",
                "Acredito que amor e respeito andam juntos.",
                "Apaixonada por viagens e novas experiências.",
                "Cristã convicta e cheia de alegria.",
                "Amo boas conversas e bons cafés."
            ]
        ];

        return fake()->randomElement($bios[$gender]);
    }
}
