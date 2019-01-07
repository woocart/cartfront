"""Validate that localization files conform to defined schemas."""

import codecs
import json
import sys
from pathlib import Path

import requests
import structlog
import yaml
from phply import phplex, phpparse

log = structlog.getLogger(__name__)


def parse_php(php_file: Path):
    """Parse a php file using the phply php parser."""
    phpparser = phpparse.make_parser(False)

    with codecs.open(php_file, "r", "utf-8") as f:
        php_source = f.read()
    tokens = phpparser.parse(php_source, lexer=phplex.lexer)
    return tokens


def dump(tokens):
    """Serialize the parsed php file into json so it can be inspected (helper fuction)."""

    # filter out non-generic tokens because they can't be easily serialized
    filtered = []
    if tokens:
        for item in tokens:
            if hasattr(item, "generic"):
                item = item.generic(with_lineno=True)
            filtered.append(item)

    # dump the rest
    json.dump(filtered, sys.stdout, indent=2)


def get_stores_from_layouts_presets():
    """Get the stores (templates) from the layouts_presets.php file."""
    root: Path = Path(__file__).resolve().parent.parent
    php_file: Path = root.joinpath("framework/classes/layouts_presets.php")

    # parse the file
    tokens = parse_php(php_file)
    # uncomment to see the tokens
    # dump(tokens)

    # find the $stores node, and fetch its values
    stores_node_name = "$stores"
    stores_node = find_node(tokens[0], stores_node_name)
    if not stores_node:
        log.error(
            "Could not find %s node in layouts_presets.php file ... will not be able to validate that node against openapi.yaml."
            % stores_node_name
        )
        return []
    else:
        return extract_stores(stores_node)


def find_node(parent, name):
    """Find the $stores node in the phply-parsed layouts_presets.php file."""
    if not hasattr(parent, "nodes"):
        return None

    for node in parent.nodes:
        type = node.generic()[0] if hasattr(node, "generic") else None
        if type == "ClassVariable" and node.name == name:
            return node
        else:
            if hasattr(node, "nodes"):
                child = find_node(node, name)
                if child:
                    return child


def extract_stores(stores_node):
    """Grab the values from the $stores list """
    stores_array = stores_node.generic()[1]["initial"][1]["nodes"]
    stores = [s[1]["value"] for s in stores_array]

    return sorted(stores)


def get_stores_from_api():
    """Get stores from the openapi.yaml file"""
    url = "https://app.woocart.com/api/v1/openapi.yaml"
    r = requests.get(url, timeout=10)
    y = yaml.load(r.text)
    stores = y["components"]["schemas"]["template"]["enum"]

    return sorted(stores)


def main():
    """Run the validators."""

    log.info(
        "Validating that the stores in layouts_presets.php are the same as in openapi.yaml ..."
    )
    api_stores = get_stores_from_api()
    php_stores = get_stores_from_layouts_presets()
    log.debug(f"PHP STORES: {php_stores} ...")
    log.debug(f"API STORES: {api_stores} ...")

    missing = [s for s in api_stores if s not in php_stores]
    mistaken = [s for s in php_stores if s not in api_stores]
    if not missing and not mistaken:
        print("All done! ✨ ✨ ✨")
    else:
        if missing:
            log.error(
                f"\033[91m💥  Stores {missing} should have been in layout_presets.php but are missing.\033[0m"
            )
        if mistaken:
            log.error(
                f"\033[91m💥  Stores {mistaken} should not be layout_presets.php.\033[0m"
            )
        exit(255)


if __name__ == "__main__":
    main()
