<?php

// phpcs:disable PSR2.Methods.MethodDeclaration.Underscore

namespace App\Tests\Api;

use App\Service\Helper\TestHelper;
use App\Tests\Support\ApiTester;
use Codeception\Util\Fixtures;
use Dullahan\Main\Service\ErrorCollector;
use Dullahan\Main\Service\Util\HttpUtilService;
use Dullahan\User\Adapter\Symfony\Application\UserManageService;
use Dullahan\User\Domain\AccessControlService;
use Dullahan\User\Domain\Entity\User;
use Dullahan\User\Domain\JWSService;

abstract class BaseCestAbstract
{
    protected string $logged;

    /** @var array<int, object> $toRemove */
    protected array $toRemove = [];

    public function _before(ApiTester $I): void
    {
        $I->clearToRemove();
        $I->haveHttpHeader('Content-Type', 'application/json');

        $httpUtilService = $I->getService(HttpUtilService::class);
        $errorCollector = $I->getService(ErrorCollector::class);
        $errorCollector->clearErrors();

        if (!$this instanceof LoginInterface) {
            return;
        }

        if (!Fixtures::exists('loginData')) {
            $this->setLoginData($I);
        } else {
            $loginData = Fixtures::get('loginData');
            $created = (int) $loginData['created'];
            $exp = $httpUtilService->getTokenExpTimeSeconds();
            if ($created + $exp < time()) {
                $this->setLoginData($I);
            }
        }

        if ($this instanceof SuperUserInterface) {
            $this->loginUser($I, 'super');
        } else {
            $this->loginUser($I, 'normal');
        }
    }

    public function _after(ApiTester $I): void
    {
        $I->removeSavedEntities();
    }

    protected function logout(ApiTester $I): void
    {
        $I->logout();
        $I->haveHttpHeader('Authorization', '');
    }

    protected function loginUser(ApiTester $I, string $type): void
    {
        if (!Fixtures::exists('loginData')) {
            $I->fail('Fixture to login are not loaded yet');
        }

        $this->logged = $type;

        $loginData = Fixtures::get('loginData');
        ['token' => $token, 'role' => $role, 'csrf' => $csrf] = $loginData[$type];
        $I->haveHttpHeader('X-CSRF-Token', $csrf);
        $I->haveHttpHeader('Authorization', 'Bearer ' . $token);
    }

    /**
     * @return array{entity: User, token: string, role: array<string>}|null
     */
    protected function getLoggedUser(): ?array
    {
        $loginData = Fixtures::get('loginData');

        return $loginData[$this->logged] ?? null;
    }

    /**
     * @return array{0: array<string, mixed>, 1: User}
     */
    protected function createNewUserAndLogin(ApiTester $I): array
    {
        $userManageService = $I->getService(UserManageService::class);
        $userArr = $I->getUserArray();
        $user = $userManageService->create($userArr);
        $I->addToRemove($user);
        //        $I->amLoggedInAs($user);
        ['token' => $token, 'csrf' => $csrf] = $this->generateAuthAndCsrfToken($I, $user);
        $I->haveHttpHeader('X-CSRF-Token', $csrf);
        $I->haveHttpHeader('Authorization', 'Bearer ' . $token);

        return [
            $userArr,
            $user,
        ];
    }

    private function setLoginData(ApiTester $I): void
    {
        $user = $I->grabEntityFromRepository(User::class, ['email' => TestHelper::USER_EMAIL]);
        $superUser = $I->grabEntityFromRepository(User::class, ['email' => TestHelper::SUPER_USER_EMAIL]);

        Fixtures::add('loginData', [
            'normal' => [
                'entity' => $user,
                ...$this->generateAuthAndCsrfToken($I, $user),
                'role' => 'ROLE_USER',
            ],
            'super' => [
                'entity' => $superUser,
                ...$this->generateAuthAndCsrfToken($I, $superUser),
                'role' => 'ROLE_SUPER_USER',
            ],
            'created' => time(),
        ]);
    }

    /**
     * @return array{
     *     token: string,
     *     csrf: string,
     * }
     */
    private function generateAuthAndCsrfToken(ApiTester $I, User $user): array
    {
        $accessControl = $I->getService(AccessControlService::class);
        $jws = $I->getService(JWSService::class);
        $token = $jws->createToken($user);
        $payload = $jws->validateAndGetPayload($token);

        return [
            'token' => $token,
            'csrf' => $accessControl->generateCSRFToken($payload['session']),
        ];
    }
}
