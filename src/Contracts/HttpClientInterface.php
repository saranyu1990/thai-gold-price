<?php 

namespace GoldPrice\Contracts;

/**
 * Interface HttpClientInterface
 *
 * Defines the contract for HTTP client implementations
 * used to fetch raw HTML from a given URL.
 */
interface HttpClientInterface
{
    public function get(string $url): string;
}
?>