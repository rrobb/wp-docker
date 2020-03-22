<?php
declare(strict_types=1);

namespace WPSite\Services;

/**
 * This singleton class contains methods for retrieving hashed file names from a manifest file
 *
 * Class ManifestService
 * @package Endouble\Services
 */
class ManifestService
{
    /**
     * Assets folder relative to web root
     * Is used to determine the actual path of an asset so that its hashed
     * filename can be returned from the rev-manifest.json file that is created
     * on build by the Gulp 'css-rev' and 'js-rev' tasks.
     * @const string
     */
    public const ASSET_BASE_DIR = '/content/themes/endouble/assets';

    /**
     * File hash match pattern
     * @const string
     */
    public const FILENAME_HASH_PATTERN = '/^(.+)-' . self::HASH_PATTERN . '(\.\w{2,4})$/';

    /**
     * Regex pattern by which file names are hashed
     * @const string
     */
    public const HASH_PATTERN = '([[:alnum:]]{10})';

    /**
     * Path to where the assets revision manifest file is stored, relative to $themeDir
     * @const string
     */
    public const MANIFEST_FILENAME = 'assets/rev-manifest.json';

    /**
     * Hash map of file names and their hashed equivalents
     * @var array
     */
    private $revManifest = [];

    /**
     * Full path to active theme folder
     * @var string
     */
    private $themeDir;

    /**
     * Singleton constructor
     * Sets the manifest contents
     * @param string $themeDir
     */
    public function __construct($themeDir)
    {
        $this->setThemeDir($themeDir);
        $this->acquireManifest();
    }

    /**
     * Returns the hash with which the requested filename was rewritten
     * @param  string $filename File path expected to be relative to the assets dir in the web root
     * @return string md5 hex digest truncated to the first ten characters of that hash
     */
    public function getFilenameHash(string $filename): string
    {
        $hashedFilename = $this->getHashedFilename($filename);
        $hash = '';

        if (preg_match(self::FILENAME_HASH_PATTERN, $hashedFilename, $matches)) {
            $hash = $matches[2];
        }

        return $hash;
    }

    /**
     * Get a hashed file name
     * When a file isn't present in the manifest file or when the manifest file isn't present,
     * the unhashed filename is returned.
     *
     * @param  string $filename File path expected to be relative to the assets dir in the web root
     * @return string
     */
    public function getHashedFilename(string $filename): string
    {
        if (array_key_exists($filename, $this->revManifest)) {
            return $this->revManifest[$filename];
        }

        return $filename;
    }

    /**
     * Return a hash map of asset file names and their rewritten file names
     * The revision file from which the hash map is extracted, is expected to be
     * stored in the value that's been set for the assetBaseDir config key.
     * @return array
     */
    public function getManifest(): array
    {
        return $this->revManifest;
    }

    /**
     * Sets the contents of the generated manifest file to $revManifest
     */
    private function acquireManifest(): void
    {
        $manifestFilePath = $this->themeDir . self::MANIFEST_FILENAME;
        if (file_exists($manifestFilePath) === false) {
            return;
        }

        $manifestContents = file_get_contents($manifestFilePath, true);
        if ($manifestContents === false) {
            return;
        }

        $jsonContents = json_decode($manifestContents, true);
        if ($jsonContents !== null) {
            $this->revManifest = $jsonContents;
        }
    }

    /**
     * Sets the full path of the active theme folder
     * Making sure that the last character is a slash
     * @param string $themeDir
     */
    private function setThemeDir(string $themeDir): void
    {
        $this->themeDir = substr($themeDir, -1) !== '/' ? "{$themeDir}/" : $themeDir;
    }
}
