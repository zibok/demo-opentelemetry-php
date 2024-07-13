import React, { ReactNode, useState } from 'react';
import { Favlist } from '../types/Favlist';
import { Button, Grid, Link, Paper, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Typography } from '@mui/material';
import MovieIcon from '@mui/icons-material/Movie';
import { DataGrid, GridColDef } from '@mui/x-data-grid';
import AddFilmsToFavlistModal from './AddFilmsToFavlistModal';

type FavlistItemProps = {
    favlist: Favlist;
}

const columns: GridColDef[] = [
    { field: 'id', headerName: '#', width: 50 },
    { field: 'title', headerName: 'Titre', width: 315 },
    { field: 'author', headerName: 'Réalisateur.ice.s', width: 250 },
    { field: 'genre', headerName: 'Genre', width: 250 },
];
  
export default function FavlistItem(props: FavlistItemProps): ReactNode {
    const [modalOpen, setModalOpen] = useState<boolean>(false);

    const handleClose = (event: Object, reason?: string) => {
        if (reason !== 'backdropClick') {
            setModalOpen(false);
            refresh();
        }
    }

    const refresh = () => {
        // TODO: refresh content        
    };

    const rows = props.favlist.filmList.map((film, index) => {
        return {
            id: index + 1,
            ...film,
        };
    })

    return (
        <Grid item xs={12} sm={6} key={`favlist-${props.favlist.id}`}>
        <Paper elevation={3}>
            <Typography component="h2" variant="h6"><MovieIcon />{props.favlist.name}</Typography>
            <Button variant='text' onClick={() => {setModalOpen(true)}}>Ajouter des films</Button>
            <AddFilmsToFavlistModal
                open={modalOpen}
                onClose={handleClose}
                favlistId={props.favlist.id}
            />
            <DataGrid 
                autoHeight
                columns={columns}
                rows={rows}
                initialState={{
                    pagination: {
                        paginationModel: { page: 0, pageSize: 5 },
                    },
                }}
                pageSizeOptions={[5, 10]}
            />
        </Paper>
        </Grid>
    )
}