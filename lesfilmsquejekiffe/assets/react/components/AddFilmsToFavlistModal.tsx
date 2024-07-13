import { Button, CircularProgress, Dialog, DialogActions, DialogContent, DialogTitle, FormControl, Link, TextField } from "@mui/material";
import React, { useEffect, useState } from "react";
import { Film } from "../types/Film";
import { DataGrid, GridColDef, GridRowSelectionModel } from "@mui/x-data-grid";

export type AddFilmsToFavlistModalProps = {
    open: boolean;
    onClose: (event: Object, reason: string) => void;
    favlistId: number;
};

const columns: GridColDef[] = [
    { field: 'filmId', headerName: 'ID', width: 50 },
    { field: 'title', headerName: 'Titre', width: 315 },
    { field: 'author', headerName: 'Réalisateur.ice.s', width: 250 },
    { field: 'genre', headerName: 'Genre', width: 250 },
];


export default function AddFilmsToFavlistModal(props: AddFilmsToFavlistModalProps) {
    const [loading, setLoading] = useState<boolean>(true);
    const [searchString, setSearchString] = useState<string>('');
    const [films, setFilms] = useState<Film[]>([]);
    const [selectedFilms, setSelectedFilms] = useState<Film[]>([]);


    useEffect(() => {searchTracks(searchString);}, [searchString]);

    const searchTracks = async (searchString: string) => {
        try {
            const response = await fetch(`/films/search?search=${searchString}`, {
                "headers": {
                    "Accept": "application/json"
                }
            }).then(r => r.json());
            
            setFilms(response.items);
            setLoading(false);
        } catch (err) {
            console.log(err)
        }
    };

    const addSelectedTracksToCurrentPlaylist = async () => {
        setLoading(true);
        const filmIds = selectedFilms.map(film => film.filmId);

        try {
            const response = await fetch(`/favlists/${props.favlistId}/films`, {
                method: "POST",
                body: JSON.stringify({
                    filmIds: filmIds,
                }),
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            }).then(r => r.status);

            if (response === 204) {
                props.onClose({}, "Creation successful")
            }

            setLoading(false);
        } catch (err) {
            console.log(err)
        }
   };

    const getRowId = (row: Film): number => row.filmId;

    const loadingFormBody = (
        <DialogContent>
            <CircularProgress/>
        </DialogContent>
    )

    const regularFormBody = (
        <DialogContent>
            <FormControl>
                <TextField
                    id="search-string"
                    label="Search"
                    variant="outlined"
                    required
                    onChange={e => setSearchString(e.target.value)}
                />
                <DataGrid 
                    autoHeight
                    checkboxSelection
                    columns={columns}
                    rows={films}
                    initialState={{
                        pagination: {
                            paginationModel: { page: 0, pageSize: 5 },
                        },
                    }}
                    pageSizeOptions={[5, 10]}
                    getRowId={getRowId}
                    
                    onRowSelectionModelChange={(rowSelectionModel: GridRowSelectionModel) => {
                        const selectedIDs = new Set(rowSelectionModel);
                        const selectedRows = films.filter((row) =>
                          selectedIDs.has(row.filmId),
                        );
              
                        setSelectedFilms(selectedRows);
                      }}
                />
            </FormControl>
        </DialogContent>
    )

    return (
        <Dialog
            open={props.open}
            onClose={props.onClose}
            aria-labelledby="modal-modal-title"
            aria-describedby="modal-modal-description"
        >
            <DialogTitle>Ajouter des films à une liste de favoris</DialogTitle>
            {loading ? loadingFormBody : regularFormBody}
            <DialogActions>
                <Button onClick={() => addSelectedTracksToCurrentPlaylist()} variant="outlined">Ajouter des films</Button>
            </DialogActions>
        </Dialog>
    );
}