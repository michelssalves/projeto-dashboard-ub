{ pkgs ? import <nixpkgs> { } }:

pkgs.mkShell {
  buildInputs = [
    pkgs.php81
    pkgs.composer
    pkgs.nodejs
    pkgs.yarn
  ];
}
