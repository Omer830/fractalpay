<?php

namespace Modules\Wallet\Contracts;

interface WalletControllerInterface
{

    public function userWallet();

    public function createAccount();

    public function getAccount();

    public function linkAccount();

    public function updateAccount();
}